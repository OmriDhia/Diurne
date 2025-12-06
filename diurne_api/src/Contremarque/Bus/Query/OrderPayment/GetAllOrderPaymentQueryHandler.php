<?php

namespace App\Contremarque\Bus\Query\OrderPayment;

use InvalidArgumentException;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\OrderPaymentRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

class GetAllOrderPaymentQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly OrderPaymentRepository $orderPaymentRepository
    )
    {
    }

    public function __invoke(GetAllOrderPaymentQuery $query): OrderPaymentQueryResponse
    {
        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            $orderPayments = array_map(
                fn(array $result) => $this->transformResult($result),
                $results
            );

            return new OrderPaymentQueryResponse(
                $orderPayments,
                $this->getMaxResult($query),
                (int)ceil($this->getMaxResult($query) / $query->itemsPerPage)
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetAllOrderPaymentQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->orderPaymentRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);
        return $stmt;
    }

    private function buildSqlQuery(GetAllOrderPaymentQuery $query): string
    {
        $sql = $this->getQueryBody();
        $sql = str_replace('_SELECT_', $this->getSelectStatement(), $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', $this->getOrderStatement($query), $sql);
        $sql = str_replace('_GROUPBY_', $this->getGroupByStatement(), $sql);
        $sql .= ' LIMIT :itemsPerPage OFFSET :offset';
        return $sql;
    }

    private function getQueryBody(): string
    {
        return '
        SELECT _SELECT_
        FROM `order_payment` op
        LEFT JOIN `payment_type` pt ON pt.id = op.payment_method_id
        LEFT JOIN `customer` c ON c.id = op.customer_id
        LEFT JOIN `customer_group` cg ON cg.id = c.customer_group_id
        LEFT JOIN `contact_information_sheet` ccis ON ccis.id = c.contact_information_sheet_id
        LEFT JOIN (
            SELECT customer_id, id AS last_id, commercial_id,
                   ROW_NUMBER() OVER (PARTITION BY customer_id ORDER BY id DESC) AS rn
            FROM contact_commercial_history
            WHERE status_id = 1
        ) chhc ON chhc.customer_id = c.id AND chhc.rn = 1
        LEFT JOIN `user` u ON u.id = op.commercial_id
        LEFT JOIN `currency` curr ON curr.id = op.currency_id
        LEFT JOIN `tax_rule` tr ON tr.id = op.tax_rule_id
        _WHERE_
        _GROUPBY_
        _ORDERBY_
    ';
    }

    private function getSelectStatement(): string
    {
        return '
        op.id,
        op.date_of_receipt AS dateOfReceipt,
        pt.label AS paymentMethod,
        c.id AS customer_id,
        u.id AS commercial_id,
        CONCAT_WS(" ", u.firstname, u.lastname) AS commercial,
        curr.name AS currency,
        tr.tax_rate AS taxRule,
        op.account_label AS accountLabel,
        op.transaction_number AS transactionNumber,
        op.payment_amount_ht AS paymentAmountHt,
        op.payment_amount_ttc AS paymentAmountTtc,
        op.created_at AS createdAt,
        op.updated_at AS updatedAt,
        op.deleted AS deleted,
        op.deleted_at AS deletedAt,
        CASE
                WHEN (cg.name != \'Particulier (Client)\') THEN CONCAT(c.social_reason, \'(\', c.code, \')\')
                ELSE CONCAT(ccis.firstname, \' \', ccis.lastname)
            END AS customer,
        (
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    "id", opd.id,
                    "quote", IFNULL(opd.quote_id, NULL),
                    "quoteDetail", IFNULL(opd.quote_detail_id, NULL),
                    "customerInvoice", IFNULL(opd.customer_invoice_id, NULL),
                    "customerInvoiceDetail", IFNULL(opd.customer_invoice_detail_id, NULL),
                    "commandNumber", opd.command_number,
                    "orderInvoiceId", opd.order_invoice_id,
                    "rn", opd.rn,
                    "distribution", opd.distribution,
                    "allocatedAmountTtc", opd.allocated_amount_ttc,
                    "remainingAmountTtc", opd.remaining_amount_ttc,
                    "totalAmountTtc", opd.total_amount_ttc,
                    "tva", opd.tva,
                    "allocatedAmountHt", opd.allocated_amount_ht,
                    "cleared", opd.cleared,
                    "createdAt", opd.created_at,
                    "updatedAt", opd.updated_at,
                    "deleted", opd.deleted,
                    "deletedAt", opd.deleted_at
                )
            )
            FROM order_payment_detail opd
            WHERE opd.order_paiement_id = op.id AND opd.deleted = 0
        ) AS orderPaymentDetails
    ';
    }

    private function getWhereStatement(GetAllOrderPaymentQuery $query): string
    {
        $conditions = ['op.deleted = 0'];

        if (null !== $query->customer) {
            $conditions[] = '(c.social_reason LIKE :customer OR CONCAT(ccis.firstname, \' \', ccis.lastname) LIKE :customer)';
        }
        if (null !== $query->commercial) {
            $conditions[] = 'CONCAT_WS(" ", u.firstname, u.lastname) LIKE :commercial';
        }
        if (null !== $query->currency) {
            $conditions[] = 'curr.id = :currency';
        }
        if (null !== $query->minPaymentAmount) {
            $conditions[] = 'op.payment_amount_ht >= :minPaymentAmount';
        }
        if (null !== $query->maxPaymentAmount) {
            $conditions[] = 'op.payment_amount_ht <= :maxPaymentAmount';
        }
        if (null !== $query->carpetOrderId) {
            // Check if any order payment detail links (directly or via quote detail) to the carpet order
            $conditions[] = 'EXISTS (
                SELECT 1 FROM order_payment_detail opd
                LEFT JOIN quote_detail qd ON qd.id = opd.quote_detail_id
                LEFT JOIN carpet_order_detail cod ON cod.quote_detail_id = qd.id
                WHERE opd.order_paiement_id = op.id AND opd.deleted = 0 AND cod.carpet_order_id = :carpetOrderId
            )';
        }
        return count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }


    private function getGroupByStatement(): string
    {
        return 'GROUP BY 
        op.id,
        op.date_of_receipt,
        pt.label,
        c.id,
        u.id,
        curr.name,
        tr.tax_rate,
        op.account_label,
        op.transaction_number,
        op.payment_amount_ht,
        op.payment_amount_ttc,
        op.created_at,
        op.updated_at,
        op.deleted,
        op.deleted_at,
        c.social_reason,
        c.code,
        ccis.firstname,
        ccis.lastname,
        cg.name';
    }

    private function getOrderStatement(GetAllOrderPaymentQuery $query): string
    {
        if (null === $query->orderBy || null === $query->orderWay) {
            return 'ORDER BY op.created_at DESC';
        }

        $validColumns = [
            'id' => 'op.id',
            'created_at' => 'op.created_at',
            'date_of_receipt' => 'op.date_of_receipt',
            'payment_amount_ht' => 'op.payment_amount_ht',
            'customer' => 'c.id',
            'commercial' => 'u.id'
        ];

        if (array_key_exists($query->orderBy, $validColumns)) {
            return 'ORDER BY ' . $validColumns[$query->orderBy] . ' ' . strtoupper($query->orderWay);
        }

        return 'ORDER BY op.created_at DESC';
    }

    private function bindQueryParameters($stmt, GetAllOrderPaymentQuery $query): void
    {
        $parameters = [
            ':customer' => $query->customer ? '%' . $query->customer . '%' : null,
            ':commercial' => $query->commercial ? '%' . $query->commercial . '%' : null,
            ':currency' => $query->currency,
            ':minPaymentAmount' => $query->minPaymentAmount,
            ':maxPaymentAmount' => $query->maxPaymentAmount,
            ':carpetOrderId' => $query->carpetOrderId,
        ];

        foreach ($parameters as $key => $value) {
            if (null !== $value) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }

    private function transformResult(array $result): array
    {
        return [
            'id' => $result['id'],
            'dateOfReceipt' => $result['dateOfReceipt'],
            'paymentMethod' => $result['paymentMethod'],
            'customer' => $result['customer_id'],
            'customerName' => $result['customer'],
            'commercial' => $result['commercial_id'],
            'commercialName' => $result['commercial'],
            'currency' => $result['currency'],
            'taxRule' => $result['taxRule'],
            'accountLabel' => $result['accountLabel'],
            'transactionNumber' => $result['transactionNumber'],
            'paymentAmountHt' => $result['paymentAmountHt'],
            'paymentAmountTtc' => $result['paymentAmountTtc'],
            'orderPaymentDetails' => json_decode($result['orderPaymentDetails'] ?? '[]', true),
            'createdAt' => $result['createdAt'],
            'updatedAt' => $result['updatedAt'],
            'deleted' => (bool)$result['deleted'],
            'deletedAt' => $result['deletedAt']
        ];
    }


    private function getMaxResult(GetAllOrderPaymentQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT op.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);
        $stmt = $this->orderPaymentRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }


}