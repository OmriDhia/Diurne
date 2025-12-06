<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEvents;

use DateTime;
use App\Common\Bus\Query\QueryHandler;
use App\Event\Repository\EventRepository;

final readonly class GetEventsQueryHandler implements QueryHandler
{
    public function __construct(
        private EventRepository $eventRepository
    ) {
    }

    public function __invoke(GetEventsQuery $query)
    {
        $sql = $this->buildQuery($query, $this->getSelectStatement());

        $stmt = $this->eventRepository->getEntityManager()->getConnection()->prepare($sql);
        $results = $stmt->executeQuery()->fetchAllAssociative();

        return new GetEventsResponse(
            (int) $this->getMaxResult($query),
            (int) $query->getPage(),
            (int) $query->getItemsPerPage(),
            $results
        );
    }

    private function buildQuery(GetEventsQuery $query, string $selectStatement, $renderCount = false): string
    {
        $sql = $this->getQueryBody();
        $groupBy = '';
        if (!$renderCount && $query->getHasOnlyOneContact()) {
            $groupBy = ' GROUP BY e.customer_id';
        }
        if (!$renderCount && null !== $query->getOnlyLastEvent() && null == $query->getHasOnlyOneContact()) {
            $groupBy = ' GROUP BY co.id';
        }
        if (!empty($groupBy)) {
            $sql = str_replace('_GROUPBY_', $groupBy, $sql);
        }

        $sql = str_replace(['_SELECT_', '_WHERE_', '_GROUPBY_', '_JOIN_'], [
            $selectStatement,
            $this->getWhereStatement($query),
            $groupBy,
            $query->getOnlyLastEvent() ? 'INNER JOIN (
                        SELECT customer_id, MAX(event_date) AS latest_event_date
                        FROM event
                        GROUP BY customer_id
                    ) AS latest_event ON e.customer_id = latest_event.customer_id AND e.event_date = latest_event.latest_event_date' : '',
        ], $sql);
        if (null !== $query->getOrderBy() && null !== $query->getOrderWay()) {
            $sql = str_replace('_ORDERBY_', $this->getOrderStatement($query), $sql);
        } else {
            $sql = str_replace('_ORDERBY_', ' ORDER BY e.id ASC', $sql);
        }
        if (!$renderCount) {
            $sql .= sprintf(' LIMIT %d OFFSET %d;', $query->getItemsPerPage(), intval($query->getItemsPerPage() * ($query->getPage() - 1)));
        }

        return $sql;
    }

    private function getQueryBody(): string
    {
        return 'SELECT _SELECT_
                    FROM event e
                    LEFT JOIN event_nomenclature en ON en.id = e.nomenclature_id
                    LEFT JOIN customer c ON e.customer_id = c.id
                    LEFT JOIN contact_information_sheet ccis ON ccis.id = c.contact_information_sheet_id
                    LEFT JOIN (
                        SELECT ch.customer_id, ch.commercial_id
                        FROM contact_commercial_history ch
                        WHERE ch.to_date = (
                            SELECT MAX(ch2.to_date)
                            FROM contact_commercial_history ch2
                            WHERE ch2.customer_id = ch.customer_id
                        )
                        GROUP BY ch.customer_id
                    ) AS cch ON cch.customer_id = c.id
                    LEFT JOIN (
                        SELECT cis.*, ct.customer_id
                        FROM contact ct
                        INNER JOIN contact_information_sheet cis ON cis.id = ct.contact_information_sheet_id
                    ) AS co ON co.customer_id = c.id
                    LEFT JOIN user u ON u.id = cch.commercial_id
                    LEFT JOIN customer_group cg ON cg.id = c.customer_group_id
                    LEFT JOIN
                    `customer_intermediary_history` cih ON cih.customer_id = c.id
                     LEFT JOIN
                     `intermediary_information_sheet` i ON i.id = c.intermediary_information_sheet_id
                      LEFT JOIN
                       `intermediary_type` it ON it.id = i.intermediary_type_id
                    LEFT JOIN `contact_information_sheet` cis_i ON (cis_i.id = c.contact_information_sheet_id)
                    _JOIN_
                    _WHERE_
                    _GROUPBY_
                    _ORDERBY_
                    ';
    }

    private function getMaxResult(GetEventsQuery $query): int
    {
        $sql = $this->buildQuery($query, $query->getOnlyLastEvent() ? 'COUNT(DISTINCT e.customer_id)' : 'COUNT(DISTINCT e.id)', true);
        $stmt = $this->eventRepository->getEntityManager()->getConnection()->prepare($sql);

        return (int) $stmt->executeQuery()->fetchOne();
    }

    private function getSelectStatement(): string
    {
        return 'DISTINCT e.contremarque_id, e.customer_id,
                    CASE
                    WHEN (cg.name!=\'Particulier (Client)\') THEN CONCAT(c.social_reason, "(", c.code, ")")
                    ELSE CONCAT(ccis.firstname, " ", ccis.lastname)
                     END    
                    AS customer,
                    CONCAT(u.firstname, \' \', u.lastname) AS commercial,
                    CONCAT(co.firstname, \' \', co.lastname) AS contact,
                    co.phone,
                    co.mobile_phone,
                    co.email,
                    en.subject,
                    e.event_date,
                    e.next_reminder_deadline AS next_step';
    }

    private function getWhereStatement(GetEventsQuery $query): string
    {
        $conditions = ['1=1', 'c.deleted_at IS NULL'];

        $this->addCondition($conditions, 'CONCAT(u.firstname, " ", u.lastname)', $query->getCommercial());
        $this->addCondition($conditions, 'c.active', $query->getActive());
        $this->addCondition($conditions, 'c.social_reason', $query->getSocialReason());
        $this->addCondition($conditions, 'c.tva_ce', $query->getTvaCe());
        $this->addCondition($conditions, 'c.website', $query->getWebsite());
        $this->addCondition($conditions, 'CONCAT(co.firstname, " ", co.lastname)', $query->getContact());
        $this->addCondition($conditions, 'CONCAT(co.firstname, " ", co.lastname)', $query->getContact());
        $this->addCondition($conditions, 'co.email', $query->getEmail());
        $this->addDateCondition($conditions, 'e.event_date', $query->getEventDateFrom(), '>=');
        $this->addDateCondition($conditions, 'e.event_date', $query->getEventDateTo(), '<');
        $this->addDateCondition($conditions, 'e.next_reminder_deadline', $query->getNextReminderDeadlineFrom(), '>=');
        $this->addDateCondition($conditions, 'e.next_reminder_deadline', $query->getNextReminderDeadlineTo(), '<');
        $this->addCondition($conditions, 'en.subject', $query->getSubject());
        $this->addCondition($conditions, 'e.nomenclature_id', $query->getNomenclatureId());
        $this->addCondition($conditions, 'e.contremarque_id', $query->getContremarqueId(), '=');
        $this->addCondition($conditions, 'e.customer_id', $query->getCustomerId(), '=');
        if (!empty($query->getFirstname())) {
            $conditions[] = '(co.firstname LIKE \'%'.$query->getFirstname().'%\' OR c.social_reason LIKE \'%'.$query->getFirstname().'%\' OR CONCAT(ccis.firstname, " ", ccis.lastname) LIKE \'%'.$query->getFirstname().'%\')';
        }
        if (!empty($query->getLastname())) {
            $conditions[] = '(co.lastname LIKE \'%'.$query->getLastname().'%\' OR c.social_reason LIKE \'%'.$query->getLastname().'%\' OR CONCAT(ccis.firstname, " ", ccis.lastname) LIKE \'%'.$query->getLastname().'%\')';
        }
        if (!empty($query->getCustomerGroups())) {
            $conditions[] = 'c.customer_group_id IN('.$query->getCustomerGroups().')';
        }
        if (null !== $query->getHasInvalidCommercial()) {
            $conditions[] = $query->getHasInvalidCommercial() ?
                'EXISTS (SELECT 1 FROM contact_commercial_history cch
                    INNER JOIN attribution_status s ON(s.id=cch.status_id)
                    WHERE cch.customer_id=c.id
                    AND s.name !=\'Accepted\')' :
                'NOT EXISTS (SELECT 1 FROM contact_commercial_history cch
                    INNER JOIN attribution_status s ON(s.id=cch.status_id)
                    WHERE cch.customer_id=c.id
                    AND s.name !=\'Accepted\')';
        }
        if (null !== $query->getPrescripteur()) {
            $conditions[] = 'CONCAT(cis_i.firstname, " ", cis_i.lastname) LIKE \'%'.$query->getPrescripteur().'%\' AND it.name LIKE \'%Prescripteur%\'';
        }
        if (null !== $query->getOnlyLastEvent()) {
            $conditions[] = 'e.event_date IN (SELECT MAX(event_date) AS latest_event_date FROM event GROUP BY customer_id)';
        }

        if (null !== $query->getHasNextStep()) {
            $conditions[] = 'e.next_reminder_deadline IS NOT NULL';
        }
        if (null !== $query->getHasNoProject()) {
            $conditions[] = 'NOT EXISTS (SELECT 1 FROM contremarque WHERE customer_id=c.id)';
        }

        return 'WHERE '.implode(' AND ', $conditions);
    }

    private function addCondition(array &$conditions, string $field, $value, string $operator = 'LIKE'): void
    {
        if (null !== $value && 'LIKE' === $operator) {
            $conditions[] = sprintf('%s %s \'%%%s%%\'', $field, $operator, $value);
        }
        if ('LIKE' !== $operator && is_int($value)) {
            $conditions[] = sprintf('%s %s %s', $field, $operator, $value);
        }
        if ('LIKE' !== $operator && is_string($value)) {
            $conditions[] = sprintf('%s %s \'%s\'', $field, $operator, $value);
        }
    }

    private function addDateCondition(array &$conditions, string $field, ?string $date, string $operator): void
    {
        if (null !== $date) {
            $conditions[] = sprintf('%s %s \'%s\'', $field, $operator, $this->formatDate($date));
        }
    }

    private function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string
    {
        return (new DateTime($date))->format($format);
    }

    private function getOrderStatement(GetEventsQuery $query): string
    {
        $sql = '';

        if (null !== $query->getOrderBy() && in_array($query->getOrderBy(), ['id', 'customer', 'commercial', 'contact', 'phone', 'mobile_phone', 'email', 'event_date', 'event_name'])) {
            match ($query->getOrderBy()) {
                'customer' => $sql .= ' ORDER BY CONCAT(c.social_reason, "(", c.code, ")")',
                'commercial' => $sql .= ' ORDER BY CONCAT(u.firstname, " ", u.lastname)',
                'contact' => $sql .= ' ORDER BY CONCAT(co.firstname, " ", co.lastname)',
                'phone' => $sql .= ' ORDER BY co.phone',
                'mobile_phone' => $sql .= ' ORDER BY co.mobile_phone',
                'email' => $sql .= ' ORDER BY co.email',
                'event_date' => $sql .= ' ORDER BY e.event_date',
                'event_name' => $sql .= ' ORDER BY en.subject',
                'id' => $sql .= ' ORDER BY e.id',
                default => $sql .= ' ORDER BY e.id',
            };
        }

        if (null !== $query->getOrderWay() && in_array(strtoupper($query->getOrderWay()), ['DESC', 'ASC'])) {
            $sql .= ' '.strtoupper($query->getOrderWay());
        }

        return $sql;
    }
}
