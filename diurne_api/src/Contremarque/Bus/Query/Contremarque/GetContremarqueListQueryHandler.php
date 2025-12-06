<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Contremarque;

use RuntimeException;
use Doctrine\DBAL\Statement;
use DateTime;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ContremarqueRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

class GetContremarqueListQueryHandler implements QueryHandler
{
    private const VALID_ORDER_COLUMNS = [
        'id' => 'c0_.id',
        'contremarque_id' => 'c0_.id',
        'project_number' => 'c0_.project_number',
        'designation' => 'c0_.designation',
        'customer_name' => 'customer_name',
        'createdAt' => 'c0_.created_at',
        'target_date' => 'c0_.target_date',
        'commercial_name' => 'commercial_name',
        'lastEvent' => 'latest_event_subject',
        'lastEventDate' => 'latest_event_date',
        'relanceDate' => 'latest_event.next_reminder_deadline',

    ];

    private ?User $currentUser = null;

    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly UserRepository         $userRepository
    )
    {
    }

    public function __invoke(GetContremarqueListQuery $query): GetContremarqueListResponse
    {
        $this->currentUser = $this->userRepository->find($query->getCurrentUser());

        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            $response = new GetContremarqueListResponse(
                $this->getMaxResult($this->getQueryBody(), $query),
                (int)$query->getPage(),
                (int)$query->getLimit(),
                $results
            );
            $response->setContremarqueRepository($this->contremarqueRepository);

            return $response;
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    /**
     * Prepare the query statement.
     *
     * @param GetContremarqueListQuery $query
     *
     * @return Statement
     */
    private function prepareQuery(GetContremarqueListQuery $query): Statement
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);

        $stmt->bindValue(':itemsPerPage', (int)$query->getLimit(), ParameterType::INTEGER);
        $stmt->bindValue(':offset', (int)($query->getLimit() * ($query->getPage() - 1)), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetContremarqueListQuery $query): string
    {
        $sql = $this->getQueryBody();
        return str_replace(
                ['_SELECT_', '_WHERE_', '_GROUPBY_', '_ORDERBY_'],
                [
                    $this->getSelectStatement(),
                    $this->getWhereStatement($query),
                    ' GROUP BY c0_.id ',
                    $this->getOrderStatement($query)
                ],
                $sql
            ) . ' LIMIT :itemsPerPage OFFSET :offset';
    }

    private function getQueryBody(): string
    {
        return '
            SELECT _SELECT_
            FROM contremarque c0_
            INNER JOIN customer c1_ ON c0_.customer_id = c1_.id
            LEFT JOIN customer_group c1g ON c1g.id = c1_.customer_group_id
            LEFT JOIN contact_information_sheet c1i_ ON c1_.contact_information_sheet_id = c1i_.id
            LEFT JOIN (
                SELECT customer_id, commercial_id,
                       ROW_NUMBER() OVER (PARTITION BY customer_id ORDER BY from_date DESC) AS rn
                FROM contact_commercial_history
                WHERE status_id = 1
            ) c4_ ON c4_.customer_id = c1_.id AND c4_.rn = 1
            LEFT JOIN customer ct ON c0_.prescriber_id = ct.id
            LEFT JOIN customer_group cg ON cg.id = ct.customer_group_id
            LEFT JOIN intermediary_information_sheet i5_ ON ct.intermediary_information_sheet_id = i5_.id
            LEFT JOIN project_di di ON di.contremarque_id = c0_.id
            LEFT JOIN carpet_design_order cdo ON di.id = cdo.project_di_id
            LEFT JOIN intermediary_type it ON it.id = i5_.intermediary_type_id
            LEFT JOIN contact_information_sheet c3_ ON ct.contact_information_sheet_id = c3_.id
            LEFT JOIN user u2_ ON c4_.commercial_id = u2_.id
            LEFT JOIN (
                SELECT e.*, en.subject
                FROM event e
                INNER JOIN event_nomenclature en ON e.nomenclature_id = en.id
                WHERE e.event_date = (
                    SELECT MAX(e2.event_date)
                    FROM event e2
                    WHERE e2.contremarque_id = e.contremarque_id
                )
            ) latest_event ON c0_.id = latest_event.contremarque_id
            _WHERE_
            _GROUPBY_
            _ORDERBY_
        ';
    }

    private function getSelectStatement(): string
    {
        return '
            c0_.id AS id_0,
            c0_.project_number AS project_number_1,
            c0_.designation AS designation_2,
            c0_.destination_location AS destination_location_3,
            c0_.target_date AS target_date_4,
            c0_.commission AS commission_5,
            c0_.commission_on_deposit AS commission_on_deposit_6,
            c0_.created_at AS created_at_7,
            c0_.updated_at AS updated_at_8,
            CASE
                WHEN c1g.name != "Particulier (Client)" THEN CONCAT(c1_.social_reason, "(", c1_.code, ")")
                ELSE CONCAT_WS(" ", c1i_.firstname, c1i_.lastname)
            END AS customer_name,
            CONCAT_WS(" ", u2_.firstname, u2_.lastname) AS commercial_name,
            CONCAT_WS(" ", c3_.firstname, c3_.lastname) AS prescriber_name,
            c0_.customer_id AS customer_id_12,
            c0_.customer_discount_id AS customer_discount_id_13,
            c0_.prescriber_id AS prescriber_id_14,
            latest_event.subject AS latest_event_subject,
            latest_event.event_date AS latest_event_date,
            latest_event.*
        ';
    }

    private function getWhereStatement(GetContremarqueListQuery $query): string
    {
        $conditions = [];

        if ($query->getDesignation() !== null) {
            $conditions[] = 'c0_.designation LIKE :designation';
        }
        if ($query->getCustomerId() !== null) {
            $conditions[] = 'c0_.customer_id = :customerId';
        }
        if ($query->getContremarqueId() !== null) {
            $conditions[] = 'c0_.id = :contremarqueId';
        }
        if ($query->getCommercialId() !== null) {
            $conditions[] = 'c4_.commercial_id >= :commercial_id';
        }
        if ($query->getCommercial() !== null) {
            $conditions[] = 'CONCAT_WS(" ", u2_.firstname, u2_.lastname) LIKE :commercial';
        }
        if ($query->getCreationDate() !== null) {
            $conditions[] = 'c0_.created_at BETWEEN :startOfCreationDay AND :endOfCreationDay';
        }
        if ($query->getTargetDate() !== null) {
            $conditions[] = 'c0_.target_date BETWEEN :startOfTargetDay AND :endOfTargetDay';
        }
        if ($query->getPrescripteur() !== null) {
            $conditions[] = '
                CASE 
                    WHEN (ct.is_intermediary = 1 AND cg.name = "Particulier (Client)") THEN CONCAT_WS(" ", c3_.firstname, c3_.lastname)
                    WHEN (ct.is_intermediary = 1 AND cg.name != "Particulier (Client)") THEN ct.social_reason
                    ELSE CONCAT_WS(" ", c3_.firstname, c3_.lastname)
                END LIKE :prescripteur
            ';
            $conditions[] = 'it.name LIKE "%Prescripteur%"';
        }
        if ($query->getCustomerName() !== null) {
            $conditions[] = 'c1_.social_reason LIKE :customerName';
        }
        if ($query->getRelaunchExceeded() !== null) {
            $conditions[] = 'latest_event.next_reminder_deadline IS NOT NULL';
            $conditions[] = '(latest_event.reminder_disabled IS NULL OR latest_event.reminder_disabled = 0)';
            $conditions[] = 'latest_event.next_reminder_deadline < NOW()';
        }
        if ($query->getRelaunchExceededByWeek() !== null) {
            $conditions[] = 'latest_event.next_reminder_deadline IS NOT NULL';
            $conditions[] = '(latest_event.reminder_disabled IS NULL OR latest_event.reminder_disabled = 0)';
            $conditions[] = 'latest_event.next_reminder_deadline >= NOW()';
            $conditions[] = 'latest_event.next_reminder_deadline < (NOW() + INTERVAL 7 DAY)';
        }
        if ($query->getWithoutRelaunch() !== null) {
            $conditions[] = '(latest_event.next_reminder_deadline IS NULL OR latest_event.reminder_disabled = 1)';
        }
        if ($query->getIsCurrentProject() !== null) {
            $conditions[] = 'latest_event.next_reminder_deadline IS NOT NULL';
            $conditions[] = '(latest_event.reminder_disabled IS NULL OR latest_event.reminder_disabled = 0)';
            //  $conditions[] = 'latest_event.next_reminder_deadline > NOW()';
        }

        $profile = $this->currentUser->getProfile()->getName();
        if ($profile === 'Commercial') {
            $conditions[] = 'c4_.commercial_id = :currentUserId';
        } elseif ($profile === 'Designer') {
            $conditions[] = 'di.transmitted_to_studio = true';
            $conditions[] = 'EXISTS (
                SELECT 1 
                FROM designer_assignment da 
                WHERE da.designer_id = :currentUserId 
                AND da.carpet_design_order_id = cdo.id
            )';
        } elseif ($profile === 'Designer manager') {
            $conditions[] = 'di.transmitted_to_studio = true';
        }

        return $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getOrderStatement(GetContremarqueListQuery $query): string
    {
        $order = $query->getOrder() ?? 'id';
        $orderWay = strtoupper($query->getOrderWay() ?? 'DESC');

        return isset(self::VALID_ORDER_COLUMNS[$order]) && in_array($orderWay, ['ASC', 'DESC'])
            ? " ORDER BY " . self::VALID_ORDER_COLUMNS[$order] . " {$orderWay}"
            : ' ORDER BY c0_.id DESC';
    }

    private function getMaxResult(string $sql, GetContremarqueListQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT c0_.id)', $sql);
        $sql = str_replace('LIMIT :itemsPerPage OFFSET :offset', '', $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);
        $stmt = $this->contremarqueRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }

    private function bindQueryParameters(Statement $stmt, GetContremarqueListQuery $query): void
    {
        $parameters = [
            ':designation' => $query->getDesignation() ? '%' . str_replace('%', '\\%', trim($query->getDesignation())) . '%' : null,
            ':customerId' => $query->getCustomerId(),
            ':contremarqueId' => $query->getContremarqueId(),
            ':commercial_id' => $query->getCommercialId(),
            ':commercial' => $query->getCommercial() ? '%' . str_replace('%', '\\%', $query->getCommercial()) . '%' : null,
            ':prescripteur' => $query->getPrescripteur() ? '%' . str_replace('%', '\\%', trim($query->getPrescripteur())) . '%' : null,
            ':customerName' => $query->getCustomerName() ? '%' . str_replace('%', '\\%', trim($query->getCustomerName())) . '%' : null,
        ];

        if ($query->getCreationDate() !== null) {
            $creationDate = $this->normalizeDate($query->getCreationDate());
            $parameters[':startOfCreationDay'] = $creationDate->format('Y-m-d 00:00:00');
            $parameters[':endOfCreationDay'] = $creationDate->format('Y-m-d 23:59:59');
        }

        if ($query->getTargetDate() !== null) {
            $targetDate = $this->normalizeDate($query->getTargetDate());
            $parameters[':startOfTargetDay'] = $targetDate->format('Y-m-d 00:00:00');
            $parameters[':endOfTargetDay'] = $targetDate->format('Y-m-d 23:59:59');
        }

        $profile = $this->currentUser->getProfile()->getName();
        if (in_array($profile, ['Commercial', 'Designer'])) {
            $parameters[':currentUserId'] = $this->currentUser->getId();
        }

        foreach ($parameters as $key => $value) {
            if ($value !== null) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }

    private function normalizeDate(mixed $date): DateTime
    {
        return $date instanceof DateTime ? $date : new DateTime($date);
    }
}
