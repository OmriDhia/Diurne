<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomers;

use RuntimeException;
use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerRepository;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

final class GetCustomersQueryHandler implements QueryHandler
{
    private $currentUser;
    private array $parameters = [];

    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly UserRepository $userRepository,
    ) {}

    public function __invoke(GetCustomersQuery $query): GetCustomersResponse
    {
        $this->currentUser = $this->userRepository->find((int)$query->getCurrentUserId());
        $isFetchingAll = null === $query->getPage() && null === $query->getItemsPerPage();
        $sql = $this->buildSqlQuery($query);

        // Log the SQL query for debugging
        //$interpolatedSql = $this->interpolateQuery($sql, $this->parameters);

        try {
            $stmt = $this->customerRepository->getEntityManager()->getConnection()->prepare($sql);
            $this->bindQueryParameters($stmt, $query);

            if (!$isFetchingAll) {
                $itemsPerPage = (int) $query->getItemsPerPage();
                $stmt->bindValue(':itemsPerPage', $itemsPerPage, ParameterType::INTEGER);
                $this->parameters[':itemsPerPage'] = $itemsPerPage;

                if (null !== $query->getPage()) {
                    $page = max(1, (int) $query->getPage());
                    $offset = ($page - 1) * $itemsPerPage;

                    $stmt->bindValue(':offset', $offset, ParameterType::INTEGER);
                    $this->parameters[':offset'] = $offset;
                }
            }

            $results = $stmt->execute()->fetchAllAssociative();

            // Post-process results to handle CASE and CONCAT logic
            $results = array_map(function ($row) {
                $row['customer'] = $row['cg_name'] !== 'Particulier (Client)'
                    ? sprintf('%s (%s)', $row['social_reason'], $row['code'])
                    : sprintf('%s %s', $row['cis_i_firstname'], $row['cis_i_lastname']);
                $row['commercial'] = $row['s_last_name'] !== 'Accepted'
                    ? sprintf('%s %s | %s %s', $row['accepted_u_firstname'], $row['accepted_u_lastname'], $row['last_u_firstname'], $row['last_u_lastname'])
                    : sprintf('%s %s', $row['last_u_firstname'], $row['last_u_lastname']);
                $row['last_commercial'] = sprintf('%s %s', $row['last_u_firstname'], $row['last_u_lastname']);
                $row['before_last_commercial'] = $row['s_last_name'] !== 'Accepted'
                    ? sprintf('%s %s', $row['accepted_u_firstname'], $row['accepted_u_lastname'])
                    : '';
                $row['contact'] = sprintf('%s %s', $row['co_firstname'], $row['co_lastname']);
                $row['has_wrong_address'] = $row['is_wrong'] == 1 ? 'true' : 'false';
                $row['has_completed_address'] = $row['is_wrong'] == 0 ? 'true' : 'false';
                $row['is_agent'] = $row['is_intermediary'] == 1 && stripos((string) $row['it_name'], 'Agent') !== false ? 'true' : 'false';
                $row['is_prescripteur'] = $row['is_intermediary'] == 1 && stripos((string) $row['it_name'], 'Prescripteur') !== false ? 'true' : 'false';
                return $row;
            }, $results);
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }

        $page = $isFetchingAll ? 1 : (int) $query->getPage();
        $itemsPerPage = $isFetchingAll ? count($results) : (int) $query->getItemsPerPage();

        return new GetCustomersResponse(
            (int) $this->getMaxResult($query),
            $page,
            $itemsPerPage,
            $results,
            $query->getExportFormat()
        );
    }

    private function buildSqlQuery(GetCustomersQuery $query): string
    {
        $sql = $this->getQueryBody($query);
        if (null !== $query->getExportFormat()) {
            $sql = str_replace('_SELECT_', $this->getExportSelectStatement(), $sql);
        } else {
            $sql = str_replace('_SELECT_', $this->getSelectStatement(), $sql);
        }
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        if (null !== $query->getOrderBy() && null !== $query->getOrderWay()) {
            $sql = str_replace('_ORDERBY_', $this->getOrderStatement($query), $sql);
        } else {
            $sql = str_replace('_ORDERBY_', 'ORDER BY c.id ASC', $sql);
        }
        if (null !== $query->getHasOnlyOneContact()) {
            $sql = str_replace('_GROUP_BY_', ' GROUP BY c.id ', $sql);
        } else {
            $sql = str_replace('_GROUP_BY_', ' GROUP BY co.id ', $sql);
        }
        if (null !== $query->getItemsPerPage()) {
            $sql .= ' LIMIT :itemsPerPage';

            if (null !== $query->getPage()) {
                $sql .= ' OFFSET :offset';
            }
        }

        return $sql;
    }

    private function getQueryBody(GetCustomersQuery $query): string
    {
        $cchWhereClause = '';
        if ($this->currentUser && $this->currentUser->getProfile()->getName() === 'Commercial') {
            $cchWhereClause = ' AND ch1.commercial_id = :currentUserId';
        }

        return 'SELECT
                    _SELECT_
                FROM
                    `customer` c
                    LEFT JOIN (
                        SELECT 
                            ch1.customer_id,
                            ch1.commercial_id,
                            ch1.id AS last_id,
                            ch1.to_date AS last_to_date,
                            MAX(ch2.id) AS before_last_id
                        FROM contact_commercial_history ch1
                        LEFT JOIN contact_commercial_history ch2 
                            ON ch2.customer_id = ch1.customer_id 
                            AND ch2.id < ch1.id
                        WHERE ch1.id = (
                            SELECT MAX(ch3.id)
                            FROM contact_commercial_history ch3
                            WHERE ch3.customer_id = ch1.customer_id
                        )' . $cchWhereClause . '
                        GROUP BY ch1.customer_id, ch1.commercial_id, ch1.id, ch1.to_date
                    ) AS cch ON cch.customer_id = c.id
                    LEFT JOIN contact_commercial_history cch_last ON cch_last.id = cch.last_id
                    LEFT JOIN contact_commercial_history cch_before_last ON cch_before_last.id = cch.before_last_id
                    LEFT JOIN `user` last_u ON last_u.id = cch_last.commercial_id
                    LEFT JOIN `user` accepted_u ON accepted_u.id = cch_before_last.commercial_id
                    LEFT JOIN `attribution_status` s_last ON s_last.id = cch_last.status_id
                    LEFT JOIN `attribution_status` s_before_last ON s_before_last.id = cch_before_last.status_id
                    LEFT JOIN (
                        SELECT
                            cis.*,
                            ct.customer_id, ct.mailing_with_caligraphie, ct.mailing
                        FROM
                            contact ct
                        INNER JOIN contact_information_sheet cis ON cis.id = ct.contact_information_sheet_id
                    ) AS co ON co.customer_id = c.id
                    LEFT JOIN `customer_address` ca ON ca.customer_id = c.id
                    LEFT JOIN `language` cl ON cl.id = c.mailing_language_id
                    LEFT JOIN `address` a ON a.id = ca.address_id
                    LEFT JOIN `customer_group` cg ON cg.id = c.customer_group_id
                    LEFT JOIN `address_type` at ON at.id = a.address_type_id
                    LEFT JOIN `country` cn ON cn.id = a.country_id
                    LEFT JOIN `customer_intermediary_history` cih ON cih.customer_id = c.id
                    LEFT JOIN `intermediary_information_sheet` i ON i.id = c.intermediary_information_sheet_id
                    LEFT JOIN `intermediary_type` it ON it.id = i.intermediary_type_id
                    LEFT JOIN `contact_information_sheet` cis_i ON (cis_i.id = c.contact_information_sheet_id)
                    LEFT JOIN `gender` gender ON gender.id = cis_i.gender_id
                    _WHERE_
                    _GROUP_BY_
                    _ORDERBY_
                ';
    }

    private function getExportSelectStatement(): string
    {
        return '
            c.id,
            c.social_reason,
            c.code,
            cg.name AS cg_name,
            MAX(gender.name) AS civility,
            MAX(last_u.firstname) AS last_u_firstname,
            MAX(last_u.lastname) AS last_u_lastname,
            MAX(accepted_u.firstname) AS accepted_u_firstname,
            MAX(accepted_u.lastname) AS accepted_u_lastname,
            MAX(s_last.name) AS s_last_name,
            MAX(co.email) AS email,
            MAX(a.address1) AS address,
            MAX(a.city) AS city,
            MAX(a.zip_code) AS zip_code,
            MAX(cn.name) AS country,
            MAX(a.is_wrong) AS is_wrong,
            MAX(a.comment) AS comment
        ';
    }

    private function getSelectStatement(): string
    {
        return '
            c.id,
            c.social_reason,
            c.code,
            cg.name AS cg_name,
            cis_i.firstname AS cis_i_firstname,
            cis_i.lastname AS cis_i_lastname,
            last_u.firstname AS last_u_firstname,
            last_u.lastname AS last_u_lastname,
            accepted_u.firstname AS accepted_u_firstname,
            accepted_u.lastname AS accepted_u_lastname,
            s_last.name AS s_last_name,
            co.firstname AS co_firstname,
            co.lastname AS co_lastname,
            cch_last.id AS attribution_id,
            co.phone,
            co.mobile_phone,
            co.email,
            a.is_wrong,
            c.is_intermediary,
            it.name AS it_name
        ';
    }

    private function getWhereStatement(GetCustomersQuery $query): string
    {
        $sql = 'WHERE 1=1 AND c.deleted_at IS NULL';

        if (!empty($query->getFirstname())) {
            $sql .= ' AND (co.firstname LIKE :firstname OR c.social_reason LIKE :firstname OR CONCAT(co.firstname, " ", co.lastname) LIKE :firstname)';
        }
        if (!empty($query->getLastname())) {
            $sql .= ' AND (co.lastname LIKE :lastname OR c.social_reason LIKE :lastname OR CONCAT(co.firstname, " ", co.lastname) LIKE :lastname)';
        }
        if (null !== $query->getCustomerName()) {
            $sql .= ' AND CASE WHEN (cg.name != \'Particulier (Client)\') THEN CONCAT(c.social_reason, "(", c.code, ")") ELSE CONCAT(cis_i.firstname, " ", cis_i.lastname) END LIKE :customerName';
        }
        if (null !== $query->getCommercial()) {
            $sql .= ' AND CONCAT(last_u.firstname, " ", last_u.lastname) LIKE :commercial';
        }
        if (null !== $query->getActive()) {
            $sql .= ' AND c.active = :active';
        }
        if (null !== $query->getHasInvalidCommercial()) {
            $sql .= $query->getHasInvalidCommercial()
                ? ' AND EXISTS (SELECT 1 FROM contact_commercial_history cch LEFT JOIN attribution_status ass ON ass.id = cch.status_id WHERE cch.customer_id = c.id AND ass.name = \'Pending\')'
                : ' AND NOT EXISTS (SELECT 1 FROM contact_commercial_history cch LEFT JOIN attribution_status ass ON ass.id = cch.status_id WHERE cch.customer_id = c.id AND ass.name = \'Pending\')';
        }
        if (null !== $query->getSocialReason()) {
            $sql .= ' AND c.social_reason LIKE :socialReason';
        }
        if (null !== $query->getTvaCe()) {
            $sql .= ' AND c.tva_ce = :tvaCe';
        }
        if (null !== $query->getWebsite()) {
            $sql .= ' AND c.website LIKE :website';
        }
        if (!empty($query->getCustomerGroups())) {
            $customerGroups = array_filter(explode(',', $query->getCustomerGroups()));
            if (!empty($customerGroups)) {
                $placeholders = implode(',', array_map(fn($i) => ":customerGroupId$i", range(0, count($customerGroups) - 1)));
                $sql .= ' AND c.customer_group_id IN(' . $placeholders . ')';
            }
        }
        if (null !== $query->getCity()) {
            $sql .= ' AND a.city LIKE :city';
        }
        if (null !== $query->getZipCode()) {
            $sql .= ' AND a.zip_code LIKE :zipCode';
        }
        if (null !== $query->getCountryId()) {
            $sql .= ' AND a.country_id = :countryId';
        }
        if (null !== $query->getMailingLanguageId()) {
            $sql .= ' AND cl.id = :mailingLanguageId';
        }
        if (null !== $query->getHasWrongAddress()) {
            $sql .= 1 === (int) $query->getHasWrongAddress() ? ' AND a.is_wrong = 1' : ' AND a.is_wrong = 0';
        }
        if (null !== $query->getHasValidAddress()) {
            $sql .= 1 === (int) $query->getHasValidAddress() ? ' AND a.is_wrong = 0' : ' AND a.is_wrong = 1';
        }
        if (null !== $query->getContactMailing()) {
            if ('Uniquement avec calligraphie' === $query->getContactMailing() || 2 === (int) $query->getContactMailing()) {
                $sql .= ' AND co.mailing_with_caligraphie = 1';
            } elseif ('Uniquement sans calligraphie' === $query->getContactMailing() || 3 === (int) $query->getContactMailing()) {
                $sql .= ' AND co.mailing_with_caligraphie = 0';
            } elseif (4 === (int) $query->getContactMailing()) {
                $sql .= ' AND (co.mailing = 1 OR co.mailing = 0 OR co.mailing_with_caligraphie = 0 OR co.mailing_with_caligraphie = 1)';
            } elseif (1 === (int) $query->getContactMailing()) {
                $sql .= ' AND (co.mailing = 1)';
            }
        }
        if (null !== $query->getPrescripteur()) {
            $sql .= ' AND CASE WHEN (c.is_intermediary = 1 AND cg.name = \'Particulier (Client)\') THEN CONCAT(cis_i.firstname, " ", cis_i.lastname) WHEN (c.is_intermediary = 1 AND cg.name != \'Particulier (Client)\') THEN c.social_reason ELSE CONCAT(cis_i.firstname, " ", cis_i.lastname) END LIKE :prescripteur';
            $sql .= ' AND it.name LIKE \'%Prescripteur%\'';
        }
        if (null !== $query->isAgent()) {
            $sql .= ' AND c.is_intermediary = 1 AND it.name LIKE \'%Agent%\'';
        }
        if (null !== $query->getIsIntermediary()) {
            $sql .= ' AND c.is_intermediary = :isIntermediary';
        }
        if (null !== $query->isPrescripteur()) {
            $sql .= ' AND c.is_intermediary = 1 AND it.name LIKE \'%Prescripteur%\'';
        }
        if (0 !== (int)$query->getCommercialId()) {
            $sql .= ' AND c.id IN (
                SELECT cch_inner.customer_id 
                FROM contact_commercial_history cch_inner 
                WHERE cch_inner.commercial_id = :commercialId
            )';
        }

        return $sql;
    }

    private function getOrderStatement(GetCustomersQuery $query): string
    {
        $sql = '';

        if (null !== $query->getOrderBy() && in_array($query->getOrderBy(), ['customer', 'commercial', 'contact', 'phone', 'mobile_phone', 'firstname', 'lastname'])) {
            match ($query->getOrderBy()) {
                'customer' => $sql .= ' ORDER BY c.social_reason, c.code',
                'commercial' => $sql .= ' ORDER BY last_u.firstname, last_u.lastname',
                'contact' => $sql .= ' ORDER BY co.firstname, co.lastname',
                'phone' => $sql .= ' ORDER BY co.phone',
                'mobile_phone' => $sql .= ' ORDER BY co.mobile_phone',
                'firstname' => $sql .= ' ORDER BY co.firstname, co.lastname',
                'lastname' => $sql .= ' ORDER BY co.firstname, co.lastname',
                default => $sql .= ' ORDER BY c.id',
            };
        }

        if (null !== $query->getOrderWay() && in_array(strtoupper($query->getOrderWay()), ['DESC', 'ASC'])) {
            $sql .= ' ' . strtoupper($query->getOrderWay());
        }

        return $sql;
    }

    private function bindQueryParameters($stmt, GetCustomersQuery $query): void
    {
        if (!empty($query->getFirstname())) {
            $this->parameters[':firstname'] = '%' . $query->getFirstname() . '%';
            $stmt->bindValue(':firstname', $this->parameters[':firstname']);
        }
        if (!empty($query->getLastname())) {
            $this->parameters[':lastname'] = '%' . $query->getLastname() . '%';
            $stmt->bindValue(':lastname', $this->parameters[':lastname']);
        }
        if (null !== $query->getCustomerName()) {
            $this->parameters[':customerName'] = '%' . ltrim($query->getCustomerName()) . '%';
            $stmt->bindValue(':customerName', $this->parameters[':customerName']);
        }
        if (null !== $query->getCommercial()) {
            $this->parameters[':commercial'] = '%' . ltrim($query->getCommercial()) . '%';
            $stmt->bindValue(':commercial', $this->parameters[':commercial']);
        }
        if (null !== $query->getActive()) {
            $this->parameters[':active'] = $query->getActive();
            $stmt->bindValue(':active', $this->parameters[':active'], ParameterType::BOOLEAN);
        }
        if (null !== $query->getSocialReason()) {
            $this->parameters[':socialReason'] = '%' . ltrim($query->getSocialReason()) . '%';
            $stmt->bindValue(':socialReason', $this->parameters[':socialReason']);
        }
        if (null !== $query->getTvaCe()) {
            $this->parameters[':tvaCe'] = $query->getTvaCe();
            $stmt->bindValue(':tvaCe', $this->parameters[':tvaCe']);
        }
        if (null !== $query->getWebsite()) {
            $this->parameters[':website'] = '%' . ltrim($query->getWebsite()) . '%';
            $stmt->bindValue(':website', $this->parameters[':website']);
        }
        if (!empty($query->getCustomerGroups())) {
            $customerGroups = array_filter(explode(',', $query->getCustomerGroups()));
            foreach ($customerGroups as $index => $groupId) {
                $paramName = ":customerGroupId$index";
                $this->parameters[$paramName] = (int) $groupId;
                $stmt->bindValue($paramName, $this->parameters[$paramName], ParameterType::INTEGER);
            }
        }
        if (null !== $query->getCity()) {
            $this->parameters[':city'] = '%' . ltrim($query->getCity()) . '%';
            $stmt->bindValue(':city', $this->parameters[':city']);
        }
        if (null !== $query->getZipCode()) {
            $this->parameters[':zipCode'] = '%' . trim($query->getZipCode()) . '%';
            $stmt->bindValue(':zipCode', $this->parameters[':zipCode']);
        }
        if (null !== $query->getCountryId()) {
            $this->parameters[':countryId'] = $query->getCountryId();
            $stmt->bindValue(':countryId', $this->parameters[':countryId']);
        }
        if (null !== $query->getMailingLanguageId()) {
            $this->parameters[':mailingLanguageId'] = $query->getMailingLanguageId();
            $stmt->bindValue(':mailingLanguageId', $this->parameters[':mailingLanguageId'], ParameterType::INTEGER);
        }
        if (null !== $query->getPrescripteur()) {
            $this->parameters[':prescripteur'] = '%' . ltrim($query->getPrescripteur()) . '%';
            $stmt->bindValue(':prescripteur', $this->parameters[':prescripteur']);
        }
        if (null !== $query->getIsIntermediary()) {
            $this->parameters[':isIntermediary'] = $query->getIsIntermediary() ? 1 : 0;
            $stmt->bindValue(':isIntermediary', $this->parameters[':isIntermediary'], ParameterType::INTEGER);
        }
        if (0 !== (int)$query->getCommercialId()) {
            $this->parameters[':commercialId'] = $query->getCommercialId();
            $stmt->bindValue(':commercialId', $this->parameters[':commercialId'], ParameterType::INTEGER);
        }
        if ($this->currentUser && $this->currentUser->getProfile()->getName() === 'Commercial') {
            $this->parameters[':currentUserId'] = $this->currentUser->getId();
            $stmt->bindValue(':currentUserId', $this->parameters[':currentUserId'], ParameterType::INTEGER);
        }
    }

    private function bindMaxResultQueryParameters($stmt, GetCustomersQuery $query): void
    {
        if (!empty($query->getFirstname())) {
            $this->parameters[':firstname'] = '%' . $query->getFirstname() . '%';
            $stmt->bindValue(':firstname', $this->parameters[':firstname']);
        }
        if (!empty($query->getLastname())) {
            $this->parameters[':lastname'] = '%' . $query->getLastname() . '%';
            $stmt->bindValue(':lastname', $this->parameters[':lastname']);
        }
        if (null !== $query->getCustomerName()) {
            $this->parameters[':customerName'] = '%' . ltrim($query->getCustomerName()) . '%';
            $stmt->bindValue(':customerName', $this->parameters[':customerName']);
        }
        if (null !== $query->getCommercial()) {
            $this->parameters[':commercial'] = '%' . ltrim($query->getCommercial()) . '%';
            $stmt->bindValue(':commercial', $this->parameters[':commercial']);
        }
        if (null !== $query->getActive()) {
            $this->parameters[':active'] = $query->getActive();
            $stmt->bindValue(':active', $this->parameters[':active'], ParameterType::BOOLEAN);
        }
        if (null !== $query->getSocialReason()) {
            $this->parameters[':socialReason'] = '%' . ltrim($query->getSocialReason()) . '%';
            $stmt->bindValue(':socialReason', $this->parameters[':socialReason']);
        }
        if (null !== $query->getTvaCe()) {
            $this->parameters[':tvaCe'] = $query->getTvaCe();
            $stmt->bindValue(':tvaCe', $this->parameters[':tvaCe']);
        }
        if (null !== $query->getWebsite()) {
            $this->parameters[':website'] = '%' . ltrim($query->getWebsite()) . '%';
            $stmt->bindValue(':website', $this->parameters[':website']);
        }
        if (!empty($query->getCustomerGroups())) {
            $customerGroups = array_filter(explode(',', $query->getCustomerGroups()));
            foreach ($customerGroups as $index => $groupId) {
                $paramName = ":customerGroupId$index";
                $this->parameters[$paramName] = (int) $groupId;
                $stmt->bindValue($paramName, $this->parameters[$paramName], ParameterType::INTEGER);
            }
        }
        if (null !== $query->getCity()) {
            $this->parameters[':city'] = '%' . ltrim($query->getCity()) . '%';
            $stmt->bindValue(':city', $this->parameters[':city']);
        }
        if (null !== $query->getZipCode()) {
            $this->parameters[':zipCode'] = '%' . trim($query->getZipCode()) . '%';
            $stmt->bindValue(':zipCode', $this->parameters[':zipCode']);
        }
        if (null !== $query->getCountryId()) {
            $this->parameters[':countryId'] = $query->getCountryId();
            $stmt->bindValue(':countryId', $this->parameters[':countryId']);
        }
        if (null !== $query->getMailingLanguageId()) {
            $this->parameters[':mailingLanguageId'] = $query->getMailingLanguageId();
            $stmt->bindValue(':mailingLanguageId', $this->parameters[':mailingLanguageId'], ParameterType::INTEGER);
        }
        if (null !== $query->getPrescripteur()) {
            $this->parameters[':prescripteur'] = '%' . ltrim($query->getPrescripteur()) . '%';
            $stmt->bindValue(':prescripteur', $this->parameters[':prescripteur']);
        }
        if (null !== $query->getIsIntermediary()) {
            $this->parameters[':isIntermediary'] = $query->getIsIntermediary() ? 1 : 0;
            $stmt->bindValue(':isIntermediary', $this->parameters[':isIntermediary'], ParameterType::INTEGER);
        }
        if (0 !== (int)$query->getCommercialId()) {
            $this->parameters[':commercialId'] = $query->getCommercialId();
            $stmt->bindValue(':commercialId', $this->parameters[':commercialId'], ParameterType::INTEGER);
        }
        if ($this->currentUser && $this->currentUser->getProfile()->getName() === 'Commercial') {
            $this->parameters[':currentUserId'] = $this->currentUser->getId();
            $stmt->bindValue(':currentUserId', $this->parameters[':currentUserId'], ParameterType::INTEGER);
        }
    }

    private function getMaxResult(GetCustomersQuery $query): int
    {
        $sql = $this->getQueryBody($query);
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT c.id) AS total', $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_GROUP_BY_', '', $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);

        // Log the SQL query for debugging
        // $interpolatedSql = $this->interpolateQuery($sql, $this->parameters);

        try {
            $stmt = $this->customerRepository->getEntityManager()->getConnection()->prepare($sql);
            $this->bindMaxResultQueryParameters($stmt, $query);

            return (int) $stmt->executeQuery()->fetchOne();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function interpolateQuery(string $query, array $params): string
    {
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $value = "'" . addslashes($value) . "'";
            } elseif (is_null($value)) {
                $value = 'NULL';
            }
            $query = str_replace($key, (string) $value, $query);
        }

        return $query;
    }
}
