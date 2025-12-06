<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteList;

use RuntimeException;
use Doctrine\DBAL\Statement;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

class GetQuoteListQueryHandler implements QueryHandler
{
    private const VALID_ORDER_COLUMNS = [
        'id' => 'q.id',
        'reference' => 'q.reference',
        'contremarque' => 'cont.designation',
        'customer' => 'customer',
        'commercial' => 'commercial',
        'creationDate' => 'q.created_at',
        'validationDate' => 'q.validated_at',
    ];

    private ?User $currentUser = null;

    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly QuoteRepository        $quoteRepository,
        private readonly UserRepository         $userRepository
    )
    {
    }

    public function __invoke(GetQuoteListQuery $query): GetQuoteListResponse
    {
        $this->currentUser = $this->userRepository->find($query->getUserId());

        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            return new GetQuoteListResponse(
                $this->getMaxResult($this->getQueryBody(), $query),
                (int)$query->getPage(),
                (int)$query->getItemsPerPage(),
                $results
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetQuoteListQuery $query): Statement
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', (int)$query->getItemsPerPage(), ParameterType::INTEGER);
        $stmt->bindValue(':offset', (int)($query->getItemsPerPage() * ($query->getPage() - 1)), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetQuoteListQuery $query): string
    {
        $sql = $this->getQueryBody();
        return str_replace(
                ['_SELECT_', '_WHERE_', '_GROUPBY_', '_ORDERBY_'],
                [
                    $this->getSelectStatement(),
                    $this->getWhereStatement($query),
                    ' GROUP BY q.reference ',
                    $this->getOrderStatement($query)
                ],
                $sql
            ) . ' LIMIT :itemsPerPage OFFSET :offset';
    }

    private function getQueryBody(): string
    {
        return '
            SELECT _SELECT_
            FROM quote q
            INNER JOIN contremarque cont ON cont.id = q.contremarque_id
            INNER JOIN customer c ON cont.customer_id = c.id
            LEFT JOIN quote_detail qd ON qd.quote_id = q.id
            LEFT JOIN contremarque_contact cc ON cc.contremarque_id = cont.id AND cc.current = 1
            LEFT JOIN contact co ON co.id = cc.contact_id
            LEFT JOIN contact_information_sheet ccis ON ccis.id = co.contact_information_sheet_id
            LEFT JOIN customer_group cg ON cg.id = c.customer_group_id
            LEFT JOIN contact_information_sheet cis ON cis.id = c.contact_information_sheet_id
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

    private function getSelectStatement(): string
    {
        return '
            chhc.commercial_id,
            q.id AS quote_id,
            c.id AS customer_id,
            cont.id AS contremarque_id,
            q.reference,
            cont.designation,
            CASE
                WHEN cg.name != "Particulier (Client)" THEN CONCAT(c.social_reason, "(", c.code, ")")
                ELSE CONCAT_WS(" ", cis.firstname, cis.lastname)
            END AS customer,
            q.created_at,
            CONCAT_WS(" ", u.firstname, u.lastname) AS commercial,
            q.validated_at
        ';
    }

    private function getWhereStatement(GetQuoteListQuery $query): string
    {
        $conditions = [];
        $conditions[] = 'q.used_in_order IS NULL AND q.is_clone_of IS NULL';
        if ($query->getContremarque() !== null) {
            $conditions[] = 'cont.designation LIKE :designation';
        }
        if ($query->getContremarqueId() !== null) {
            $conditions[] = 'cont.id = :contremarqueId';
        }
        if ($query->getLocationId() !== null) {
            $conditions[] = 'qd.location_id = :locationId';
        }
        if ($query->getCustomer() !== null) {
            $conditions[] = '(
                CASE
                    WHEN cg.name != "Particulier (Client)" THEN CONCAT(c.social_reason, "(", c.code, ")")
                    ELSE CONCAT_WS(" ", cis.firstname, cis.lastname)
                END
            ) LIKE :customer';
        }
        if ($query->getCommercial() !== null) {
            $conditions[] = 'CONCAT_WS(" ", u.firstname, u.lastname) LIKE :commercial';
        }
        if ($query->getDevis() !== null) {
            $conditions[] = 'q.reference LIKE :devis';
        }
        if ($this->currentUser->getProfile()->getName() === 'Commercial') {
            $conditions[] = 'chhc.commercial_id = :currentUserId';
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getOrderStatement(GetQuoteListQuery $query): string
    {
        $orderBy = $query->getOrderBy() ?? 'id';
        $orderWay = strtoupper($query->getOrderWay() ?? 'DESC');

        if (isset(self::VALID_ORDER_COLUMNS[$orderBy]) && in_array($orderWay, ['ASC', 'DESC'])) {
            return " ORDER BY " . self::VALID_ORDER_COLUMNS[$orderBy] . " {$orderWay}";
        }
        return ' ORDER BY q.id DESC';
    }

    private function getMaxResult(string $sql, GetQuoteListQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT q.id)', $sql);
        $sql = str_replace('LIMIT :itemsPerPage OFFSET :offset', '', $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }

    private function bindQueryParameters(Statement $stmt, GetQuoteListQuery $query): void
    {
        $parameters = [
            ':designation' => $query->getContremarque() ? '%' . $query->getContremarque() . '%' : null,
            ':contremarqueId' => $query->getContremarqueId(),
            ':locationId' => $query->getLocationId(),
            ':customer' => $query->getCustomer() ? '%' . $query->getCustomer() . '%' : null,
            ':commercial' => $query->getCommercial() ? '%' . $query->getCommercial() . '%' : null,
            ':devis' => $query->getDevis() ? '%' . $query->getDevis() . '%' : null,
            ':currentUserId' => $this->currentUser->getProfile()->getName() === 'Commercial' ? $this->currentUser->getId() : null,
        ];

        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }
}
