<?php

namespace App\Invoices\Bus\Query\CustomerInvoice;

use App\Common\Bus\Query\QueryHandler;
use App\Invoices\Repository\CustomerInvoiceRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

final class GetCustomerInvoiceListQueryHandler implements QueryHandler
{
    public function __construct(private readonly CustomerInvoiceRepository $repository)
    {
    }

    public function __invoke(GetCustomerInvoiceListQuery $query): GetCustomerInvoiceListResponse
    {
        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            return new GetCustomerInvoiceListResponse(
                $results,
                $this->getMaxResult($query),
                $query->page,
                $query->itemsPerPage
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetCustomerInvoiceListQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->repository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':limit', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetCustomerInvoiceListQuery $query): string
    {
        $sql = $this->getQueryBody();
        $sql = str_replace('_SELECT_', $this->getSelectStatement(), $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', 'ORDER BY ci.id DESC', $sql);
        $sql = str_replace('_GROUPBY_', $this->getGroupByStatement(), $sql);

        return $sql . ' LIMIT :limit OFFSET :offset';
    }

    private function getQueryBody(): string
    {
        return '
            SELECT _SELECT_
            FROM customer_invoice ci
            LEFT JOIN customer c ON c.id = ci.customer_id
            LEFT JOIN carpet_order co ON co.id = ci.carpet_order_id
            LEFT JOIN contremarque cm ON cm.id = co.contremarque_id
            LEFT JOIN (
                SELECT customer_id, id AS last_id, commercial_id,
                       ROW_NUMBER() OVER (PARTITION BY customer_id ORDER BY id DESC) AS rn
                FROM contact_commercial_history
                WHERE status_id = 1
            ) chhc ON chhc.customer_id = c.id AND chhc.rn = 1
            _WHERE_
            _GROUPBY_
            _ORDERBY_
        ';
    }

    private function getSelectStatement(): string
    {
        return '
            ci.id,
            ci.invoice_number,
            ci.invoice_date,
            ci.customer_id,
            ci.amount_ht,
            ci.amount_tva,
            ci.amount_ttc,
            ci.payment,
            ci.billed,
            CASE WHEN ci.payment >= ci.billed THEN 1 ELSE 0 END AS cleared,
            cm.id AS contremarque_id,
            cm.designation AS contremarque,
            c.social_reason AS customer,
            chhc.commercial_id
        ';
    }

    private function getWhereStatement(GetCustomerInvoiceListQuery $query): string
    {
        $conditions = [];

        if (null !== $query->invoiceNumber) {
            $conditions[] = 'ci.invoice_number LIKE :invoiceNumber';
        }
        if (null !== $query->contremarque) {
            $conditions[] = 'cm.designation LIKE :contremarque';
        }
        if (null !== $query->fromDate) {
            $conditions[] = 'DATE(ci.invoice_date) >= :fromDate';
        }
        if (null !== $query->toDate) {
            $conditions[] = 'DATE(ci.invoice_date) <= :toDate';
        }
        if (null !== $query->cleared) {
            $conditions[] = 'CASE WHEN ci.payment >= ci.billed THEN 1 ELSE 0 END = :cleared';
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getGroupByStatement(): string
    {
        return 'GROUP BY ci.id, c.id, cm.id, chhc.commercial_id';
    }

    private function getMaxResult(GetCustomerInvoiceListQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT ci.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);

        $stmt = $this->repository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int) $stmt->executeQuery()->fetchOne();
    }

    private function bindQueryParameters($stmt, GetCustomerInvoiceListQuery $query): void
    {
        if (null !== $query->invoiceNumber) {
            $stmt->bindValue('invoiceNumber', '%' . $query->invoiceNumber . '%', ParameterType::STRING);
        }
        if (null !== $query->contremarque) {
            $stmt->bindValue('contremarque', '%' . $query->contremarque . '%', ParameterType::STRING);
        }
        if (null !== $query->fromDate) {
            $stmt->bindValue('fromDate', $query->fromDate, ParameterType::STRING);
        }
        if (null !== $query->toDate) {
            $stmt->bindValue('toDate', $query->toDate, ParameterType::STRING);
        }
        if (null !== $query->cleared) {
            $stmt->bindValue('cleared', $query->cleared ? 1 : 0, ParameterType::INTEGER);
        }
    }
}
