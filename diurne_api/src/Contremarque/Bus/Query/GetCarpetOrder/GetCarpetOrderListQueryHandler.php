<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrder;

use RuntimeException;
use Doctrine\DBAL\Statement;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

class GetCarpetOrderListQueryHandler implements QueryHandler
{
    /**
     *
     */
    private const VALID_ORDER_COLUMNS = [
        'id' => 'co.id',
        'reference' => 'co.reference',
        'contremarque' => 'cont.designation',
        'customer' => 'customer',
        'creationDate' => 'co.created_at',
        'originalQuote' => 'oq.reference',
        'originalQuoteReference' => 'oq.reference',
        'clonedQuote' => 'cq.reference'
    ];

    /**
     * @var User|null
     */
    private ?User $currentUser = null;

    /**
     * @param ContremarqueRepository $contremarqueRepository
     * @param CarpetOrderRepository $carpetOrderRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CarpetOrderRepository  $carpetOrderRepository,
        private readonly UserRepository         $userRepository
    )
    {
    }

    /**
     * @param GetCarpetOrderListQuery $query
     * @return GetCarpetOrderListResponse
     */
    public function __invoke(GetCarpetOrderListQuery $query): GetCarpetOrderListResponse
    {
        $this->currentUser = $this->userRepository->find($query->getUserId());

        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            return new GetCarpetOrderListResponse(
                $this->getMaxResult($this->getQueryBody(), $query),
                (int)$query->getPage(),
                (int)$query->getItemsPerPage(),
                $results
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    /**
     * @param GetCarpetOrderListQuery $query
     * @return Statement
     * @throws Exception
     */
    private function prepareQuery(GetCarpetOrderListQuery $query): Statement
    {

        $sql = $this->buildSqlQuery($query);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', (int)$query->getItemsPerPage(), ParameterType::INTEGER);
        $stmt->bindValue(':offset', (int)($query->getItemsPerPage() * ($query->getPage() - 1)), ParameterType::INTEGER);

        return $stmt;
    }

    /**
     * @param GetCarpetOrderListQuery $query
     * @return string
     */
    private function buildSqlQuery(GetCarpetOrderListQuery $query): string
    {
        $sql = $this->getQueryBody();
        $fullquery = str_replace(
                ['_SELECT_', '_WHERE_', '_GROUPBY_', '_ORDERBY_'],
                [
                    $this->getSelectStatement(),
                    $this->getWhereStatement($query),
                    ' GROUP BY co.reference ',
                    $this->getOrderStatement($query)
                ],
                $sql
            ) . ' LIMIT :itemsPerPage OFFSET :offset';
        return $fullquery;
    }

    /**
     * @return string
     */
    private function getQueryBody(): string
    {
        return '
        SELECT _SELECT_
        FROM carpet_order co
        INNER JOIN contremarque cont ON cont.id = co.contremarque_id
        INNER JOIN customer c ON cont.customer_id = c.id
        LEFT JOIN contremarque_contact cc ON cc.contremarque_id = cont.id AND cc.current = 1
        LEFT JOIN contact con ON con.id = cc.contact_id 
        LEFT JOIN contact_information_sheet ccis ON ccis.id = con.contact_information_sheet_id
        LEFT JOIN quote oq ON oq.id = co.original_quote_id
        LEFT JOIN quote cq ON cq.id = co.cloned_quote_id
        LEFT JOIN (
            SELECT customer_id, id AS last_id, commercial_id,
                   ROW_NUMBER() OVER (PARTITION BY customer_id ORDER BY id DESC) AS rn
            FROM contact_commercial_history
            WHERE status_id = 1
        ) chhc ON chhc.customer_id = c.id AND chhc.rn = 1
        LEFT JOIN user u ON chhc.commercial_id = u.id
        _WHERE_
        _GROUPBY_
        _ORDERBY_
    ';
    }

    /**
     * @return string
     */
    private function getSelectStatement(): string
    {
        return '
            co.id,
            oq.id AS original_quote,
            cq.id AS cloned_quote,
            co.reference,
            cont.id AS contremarque_id,
            cont.designation,
            CONCAT_WS(" ", ccis.firstname, ccis.lastname) AS customer,
            co.created_at,
            CONCAT_WS(" ", u.firstname, u.lastname) AS commercial,
            oq.reference AS original_quote_reference,
            cq.reference AS cloned_quote_reference
        ';
    }

    /**
     * @param GetCarpetOrderListQuery $query
     * @return string
     */
    private function getWhereStatement(GetCarpetOrderListQuery $query): string
    {
        $conditions = [];

        if ($query->getReference() !== null) {
            $conditions[] = 'co.reference LIKE :reference';
        }
        if ($query->getOriginalQuoteReference() !== null) {
            $conditions[] = 'oq.reference LIKE :originalQuoteReference';
        }
        if ($query->getContremarque() !== null) {
            $conditions[] = 'cont.designation LIKE :contremarque';
        }
        if ($query->getContremarqueId() !== null) {
            $conditions[] = 'cont.id = :contremarqueId';
        }
        if ($query->getCustomer() !== null) {
            $conditions[] = 'CONCAT_WS(" ", ccis.firstname, ccis.lastname) LIKE :customer';
        }
        if ($query->getCreationDate() !== null) {
            $conditions[] = 'DATE(co.created_at) = :creationDate';
        }
        if ($this->currentUser->getProfile()->getName() === 'Commercial') {
            $conditions[] = 'chhc.commercial_id = :currentUserId';
        }
        if ($query->getCommercial() !== null) {
            $conditions[] = 'CONCAT_WS(" ", u.firstname, u.lastname) LIKE :commercial';
        }
        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    /**
     * @param GetCarpetOrderListQuery $query
     * @return string
     */
    private function getOrderStatement(GetCarpetOrderListQuery $query): string
    {
        $orderBy = $query->getOrderBy() ?? 'id';
        $orderWay = strtoupper($query->getOrderWay() ?? 'DESC');

        if (isset(self::VALID_ORDER_COLUMNS[$orderBy]) && in_array($orderWay, ['ASC', 'DESC'])) {
            return " ORDER BY " . self::VALID_ORDER_COLUMNS[$orderBy] . " {$orderWay}";
        }
        return ' ORDER BY co.id DESC';
    }

    /**
     * @param string $sql
     * @param GetCarpetOrderListQuery $query
     * @return int
     * @throws Exception
     */
    private function getMaxResult(string $sql, GetCarpetOrderListQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT co.id)', $sql);
        $sql = str_replace('LIMIT :itemsPerPage OFFSET :offset', '', $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }

    /**
     * @param Statement $stmt
     * @param GetCarpetOrderListQuery $query
     * @return void
     * @throws Exception
     */
    private function bindQueryParameters(Statement $stmt, GetCarpetOrderListQuery $query): void
    {
        $parameters = [
            ':reference' => $query->getReference() ? '%' . $query->getReference() . '%' : null,
            ':originalQuoteReference' => $query->getOriginalQuoteReference() ? '%' . $query->getOriginalQuoteReference() . '%' : null,
            ':contremarque' => $query->getContremarque() ? '%' . $query->getContremarque() . '%' : null,
            ':contremarqueId' => $query->getContremarqueId(),
            ':customer' => $query->getCustomer() ? '%' . $query->getCustomer() . '%' : null,
            ':commercial' => $query->getCommercial() ? '%' . $query->getCommercial() . '%' : null,
            ':creationDate' => $query->getCreationDate(),
            ':currentUserId' => $this->currentUser->getProfile()->getName() === 'Commercial' ? $this->currentUser->getId() : null,
        ];

        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }
}
