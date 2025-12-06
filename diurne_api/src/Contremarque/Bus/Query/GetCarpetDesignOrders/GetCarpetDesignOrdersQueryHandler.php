<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrders;

use RuntimeException;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

final class GetCarpetDesignOrdersQueryHandler implements QueryHandler
{
    private $currentUser;

    public function __construct(
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly UserRepository              $userRepository,
    )
    {
    }

    public function __invoke(GetCarpetDesignOrdersQuery $query)
    {
        $currentUserId = $query->getCurrentUser();
        $this->currentUser = $this->userRepository->find($currentUserId);

        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            return new GetCarpetDesignOrdersResponse(
                $this->getMaxResult($query),
                $query->page,
                $query->itemsPerPage,
                $results
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetCarpetDesignOrdersQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->carpetDesignOrderRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetCarpetDesignOrdersQuery $query): string
    {
        $sql = $this->getQueryBody();

        $sql = str_replace('_SELECT_', $this->getSelectStatement(), $sql);
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', $this->getOrderStatement($query), $sql);

        $sql .= ' LIMIT :itemsPerPage OFFSET :offset';
        return $sql;
    }

    private function getQueryBody(): string
    {
        return '
            SELECT _SELECT_
            FROM `carpet_design_order` cdo
            LEFT JOIN `project_di` di ON di.id = cdo.project_di_id
            LEFT JOIN `contremarque` contremarque ON di.contremarque_id = contremarque.id
            LEFT JOIN `image_command` ic ON ic.carpet_design_order_id = cdo.id
            LEFT JOIN `customer` c ON contremarque.customer_id = c.id
            LEFT JOIN `customer_group` cg ON cg.id = c.customer_group_id
            LEFT JOIN `contact_information_sheet` ccis ON ccis.id = c.contact_information_sheet_id
            LEFT JOIN `location` l ON cdo.location_id = l.id
            LEFT JOIN `carpet_status` cs ON cs.id = cdo.status_id
            LEFT JOIN `designer_assignment` da ON da.carpet_design_order_id = cdo.id
                AND da.date_from = (
                    SELECT MAX(date_from)
                    FROM `designer_assignment` da_sub
                    WHERE da_sub.carpet_design_order_id = cdo.id
                )
            LEFT JOIN `user` u ON u.id = da.designer_id
            LEFT JOIN (
                SELECT ch.customer_id,
                       ch.id AS last_id,
                       ch.commercial_id
                FROM contact_commercial_history ch
                WHERE ch.status_id = 1
                AND ch.id IN (
                    SELECT MAX(id)
                    FROM contact_commercial_history
                    WHERE status_id = 1
                    GROUP BY customer_id
                )
            ) chhc ON chhc.customer_id = c.id
            LEFT JOIN (
                SELECT i.carpet_design_order_id,
                       iat.attachment_id,
                       ata.path,
                       ata.file,
                       it.name
                FROM `image` i
                LEFT JOIN `image_type` it ON it.id = i.image_type_id
                LEFT JOIN `image_attachment` iat ON i.id = iat.image_id
                LEFT JOIN `attachment` ata ON ata.id = iat.attachment_id
                WHERE iat.attachment_id = (
                    SELECT iat2.attachment_id
                    FROM `image_attachment` iat2
                    JOIN `image` i2 ON i2.id = iat2.image_id
                    JOIN `image_type` it2 ON it2.id = i2.image_type_id
                    WHERE i2.carpet_design_order_id = i.carpet_design_order_id
                    ORDER BY (it2.name = \'Vignette\') DESC, iat2.attachment_id ASC
                    LIMIT 1
                )
            ) img ON img.carpet_design_order_id = cdo.id
            _WHERE_
            _ORDERBY_
        ';
    }

    private function getSelectStatement(): string
    {
        return '
        chhc.commercial_id,
        c.id AS customer_id,
        di.id AS di_id,
        contremarque.id AS contremarque_id,
        cdo.id AS order_design_id,
        ic.id AS image_command_id,
        di.demande_number AS diNumber,
        di.created_at AS diDate,
        contremarque.designation AS contremarque,
        CASE
            WHEN (cg.name != \'Particulier (Client)\') THEN CONCAT(c.social_reason, \'(\', c.code, \')\')
            ELSE CONCAT(ccis.firstname, \' \', ccis.lastname)
        END AS customer,
        l.description AS location,
        di.deadline,
        da.designer_id AS lastDesignerId,
        da.date_from AS lastAssignmentDate,
        CONCAT(u.firstname, \' \', u.lastname) AS designer,
        FALSE AS wrong_image,
        cs.name AS carpet_status,
        img.attachment_id AS attach_id,
        img.path AS image_path,
        img.file AS image_name,
        img.name AS image_type,
        cdo.model_name,
        cdo.variation,
        cdo.jpeg,
        cdo.impression,
        cdo.impression_barre_de_laine
        ';
    }

    private function getWhereStatement(GetCarpetDesignOrdersQuery $query): string
    {
        $conditions = ['cdo.deleted_at IS NULL', 'cdo.deleted_by IS NULL'];



        if (null !== $query->designer) {
            $conditions[] = 'da.designer_id = :designerId';
        }

        if (null !== $query->customer) {
            $conditions[] = 'c.id = :customer';
        }
        if (null !== $query->diNumber) {
            $conditions[] = 'di.demande_number LIKE :demande_number';
        }
        if (null !== $query->contremarqueId) {
            $conditions[] = 'contremarque.id = :contremarqueId';
        }
        if (null !== $query->prescripteur) {
            $conditions[] = 'CONCAT(u.firstname, \' \', u.lastname) LIKE :prescripteur';
        }
        if (null !== $query->diId) {
            $conditions[] = 'di.id = :diId';
        }
        if (null !== $query->contremarque) {
            $conditions[] = 'contremarque.designation LIKE :contremarque';
        }
        if (null !== $query->statusId) {
            $conditions[] = 'cs.id = :statusId';
        }
        if (null !== $query->collectionId || null !== $query->modelId) {
            // Only join carpet_specification if collectionId or modelId is used
            $conditions[] = 'cdo.carpet_specification_id = csp.id';
            if (null !== $query->collectionId) {
                $conditions[] = 'csp.collection_id = :collectionId';
            }
            if (null !== $query->modelId) {
                $conditions[] = 'csp.model_id = :modelId';
            }
        }
        if (in_array($this->currentUser->getProfile()->getName(), ['Designer'])) {
            $conditions[] = 'di.transmitted_to_studio = true';
            $conditions[] = 'da.designer_id = :currentUserId';
        }
        if (in_array($this->currentUser->getProfile()->getName(), ['Designer manager'])) {
            $conditions[] = 'di.transmitted_to_studio = true';
        }
        if (in_array($this->currentUser->getProfile()->getName(), ['Commercial'])) {
            $conditions[] = 'chhc.commercial_id = :currentUserId';
        }

        return count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getOrderStatement(GetCarpetDesignOrdersQuery $query): string
    {

        if (null === $query->orderBy || null === $query->orderWay) {
            return 'ORDER BY cdo.id DESC';
        }

        $validColumns = [
            'diNumber' => 'di.demande_number',
            'diDate' => 'di.created_at',
            'customer' => 'c.id',
            'contremarque' => 'contremarque.designation',
            'location' => 'l.description',
            'designer' => 'CONCAT(u.firstname, \' \', u.lastname)',
            'lastAssignmentDate' => 'da.date_from',
            'deadline' => 'di.deadline',
            'carpet_status' => 'cs.name',
            'wrong_image' => 'cs.name',
            'order_design_id' => 'cdo.id',
        ];
        if (
            array_key_exists($query->orderBy, $validColumns)
            && in_array(strtoupper($query->orderWay), ['ASC', 'DESC'])
        ) {
            return 'ORDER BY ' . $validColumns[$query->orderBy] . ' ' . strtoupper($query->orderWay);
        }

        return '';
    }

    private function bindQueryParameters($stmt, GetCarpetDesignOrdersQuery $query): void
    {

        $parameters = [
            ':designerId' => $query->designer,
            ':customer' => $query->customer ?? null,

            ':prescripteur' => null !== $query->prescripteur ? '%' . ltrim($query->prescripteur) . '%' : null,
            ':demande_number' => null !== $query->diNumber ? '%' . ltrim($query->diNumber) . '%' : null,
            ':diId' => $query->diId,
            ':contremarque' => null !== $query->contremarque ? '%' . ltrim($query->contremarque) . '%' : null,
            ':statusId' => $query->statusId,
            ':contremarqueId' => $query->contremarqueId,
            ':collectionId' => $query->collectionId,
            ':modelId' => $query->modelId,
        ];

        if (in_array($this->currentUser->getProfile()->getName(), ['Designer', 'Commercial'])) {
            $parameters[':currentUserId'] = $this->currentUser->getId();
        }
        // if (in_array($this->currentUser->getProfile()->getName(), ['Designer manager'])) {
        //     $stmt->bindValue(':transmittedToStudio', true, ParameterType::BOOLEAN);
        // }
        foreach ($parameters as $key => $value) {
            if (null !== $value) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }

    private function getMaxResult(GetCarpetDesignOrdersQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT cdo.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);
        $stmt = $this->carpetDesignOrderRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }
}
