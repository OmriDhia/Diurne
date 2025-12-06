<?php

namespace App\Invoices\Bus\Query\SupplierInvoice;

use App\Common\Bus\Query\QueryHandler;
use App\Invoices\Repository\SupplierInvoiceRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

final class GetSupplierInvoiceListQueryHandler implements QueryHandler
{
    public function __construct(private readonly SupplierInvoiceRepository $repository)
    {
    }

    public function __invoke(GetSupplierInvoiceListQuery $query): GetSupplierInvoiceListResponse
    {
        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            return new GetSupplierInvoiceListResponse(
                $results,
                $this->getMaxResult($query),
                $query->page,
                $query->itemsPerPage
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetSupplierInvoiceListQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->repository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':limit', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetSupplierInvoiceListQuery $query): string
    {
        $sql = $this->getQueryBody();
        $sql = str_replace('_SELECT_', $this->getSelectStatement(), $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        // Map orderBy aliases to safe column names to avoid SQL injection
        $orderByMap = [
            'invoice_number' => 'si.invoice_number',
            'invoiceNumber' => 'si.invoice_number',
            'invoice_date' => 'si.invoice_date',
            'invoiceDate' => 'si.invoice_date',
            'manufacturer' => 'm.name',
            'manufacturer_name' => 'm.name',
            'author' => 'u.firstname'
        ];
        $orderBy = $query->orderBy && isset($orderByMap[$query->orderBy]) ? $orderByMap[$query->orderBy] : 'si.id';
        $orderWay = $query->orderWay && in_array(strtoupper($query->orderWay), ['ASC', 'DESC']) ? strtoupper($query->orderWay) : 'DESC';
        $sql = str_replace('_ORDERBY_', "ORDER BY $orderBy $orderWay", $sql);
        $sql = str_replace('_GROUPBY_', $this->getGroupByStatement(), $sql);

        return $sql . ' LIMIT :limit OFFSET :offset';
    }

    private function getQueryBody(): string
    {
        return '
            SELECT _SELECT_
            FROM supplier_invoice si
            LEFT JOIN `user` u ON u.id = si.author_id
            LEFT JOIN `manufacturer` m ON m.id = si.manufacturer_id
            _WHERE_
            _GROUPBY_
            _ORDERBY_
        ';
    }

    private function getSelectStatement(): string
    {
        return '
            si.id,
            si.invoice_number,
            si.invoice_date,
            m.id AS manufacturer_id,
            m.name AS manufacturer_name,
            m.company AS manufacturer_company,
            CONCAT(u.firstname, " ", u.lastname) AS author,
            (
                SELECT IFNULL(GROUP_CONCAT(DISTINCT c.rn_number SEPARATOR "-"), "")
                FROM supplier_invoice_detail sid
                LEFT JOIN carpet c ON c.id = sid.rn_id
                WHERE sid.supplier_invoice_id = si.id
            ) AS rns
        ';
    }

    private function getWhereStatement(GetSupplierInvoiceListQuery $query): string
    {
        $conditions = [];

        if (null !== $query->invoiceNumber) {
            $conditions[] = 'si.invoice_number LIKE :invoiceNumber';
        }
        if (null !== $query->authorId) {
            $conditions[] = 'si.author_id = :authorId';
        }
        // date range filter
        if (null !== $query->dateFrom) {
            $conditions[] = 'si.invoice_date >= :dateFrom';
        }
        if (null !== $query->dateTo) {
            $conditions[] = 'si.invoice_date <= :dateTo';
        }
        // filter by RN (carpet number) in related supplier invoice details
        if (null !== $query->rn) {
            $conditions[] = 'EXISTS (
                SELECT 1
                FROM supplier_invoice_detail sid2
                JOIN carpet c2 ON c2.id = sid2.rn_id
                WHERE sid2.supplier_invoice_id = si.id
                  AND c2.rn_number LIKE :rn
            )';
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getGroupByStatement(): string
    {
        return 'GROUP BY si.id, u.id, m.id, m.name, m.company';
    }

    private function getMaxResult(GetSupplierInvoiceListQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT si.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);

        $stmt = $this->repository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }

    private function bindQueryParameters($stmt, GetSupplierInvoiceListQuery $query): void
    {
        if (null !== $query->invoiceNumber) {
            $stmt->bindValue('invoiceNumber', '%' . $query->invoiceNumber . '%', ParameterType::STRING);
        }
        if (null !== $query->authorId) {
            $stmt->bindValue('authorId', $query->authorId, ParameterType::INTEGER);
        }
        if (null !== $query->rn) {
            $stmt->bindValue('rn', '%' . $query->rn . '%', ParameterType::STRING);
        }
        if (null !== $query->dateFrom) {
            // inclusive start of day
            $stmt->bindValue('dateFrom', $query->dateFrom . ' 00:00:00', ParameterType::STRING);
        }
        if (null !== $query->dateTo) {
            // inclusive end of day
            $stmt->bindValue('dateTo', $query->dateTo . ' 23:59:59', ParameterType::STRING);
        }
    }
}
