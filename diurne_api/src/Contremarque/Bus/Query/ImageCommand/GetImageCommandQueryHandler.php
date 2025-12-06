<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\ImageCommand;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\ImageCommandRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

final class GetImageCommandQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ImageCommandRepository $imageCommandRepository,
    )
    {
    }

    public function __invoke(GetImageCommandQuery $query): GetImageCommandResponse
    {
        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            $imageCommands = array_map(
                fn(array $result) => $this->transformResult($result, $query),
                $results
            );

            return new GetImageCommandResponse(
                $imageCommands,
                $this->getMaxResult($query),
                (int)ceil($this->getMaxResult($query) / $query->itemsPerPage)
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetImageCommandQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->imageCommandRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetImageCommandQuery $query): string
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
            FROM `image_command` ic
        LEFT JOIN `carpet_design_order` cdo ON cdo.id = ic.carpet_design_order_id
        LEFT JOIN `carpet_specification` cspec ON cspec.id = cdo.carpet_specification_id
        LEFT JOIN `carpet_dimension` cd ON cd.carpet_specification_id = cspec.id
        LEFT JOIN `carpet_dimension_carpet_dimension_value` cdcv ON cdcv.carpet_dimension_id = cd.id
        LEFT JOIN `carpet_dimension_value` cdv ON cdv.id = cdcv.carpet_dimension_value_id
        LEFT JOIN `project_di` di ON di.id = cdo.project_di_id
        LEFT JOIN `contremarque` cm ON di.contremarque_id = cm.id
            LEFT JOIN `customer` c ON cm.customer_id = c.id
            LEFT JOIN `customer_group` cg ON cg.id = c.customer_group_id
            LEFT JOIN `contact_information_sheet` ccis ON ccis.id = c.contact_information_sheet_id
            LEFT JOIN `location` l ON cdo.location_id = l.id
            LEFT JOIN `carpet_status` cs ON cs.id = cdo.status_id
            LEFT JOIN `image_command_designer_assignment` icda ON icda.image_command_id = ic.id
            LEFT JOIN `user` d ON d.id = icda.designer_id
            LEFT JOIN (
                SELECT ch.customer_id, ch.commercial_id,
                       ROW_NUMBER() OVER (PARTITION BY ch.customer_id ORDER BY ch.id DESC) AS rn
                FROM `contact_commercial_history` ch
                WHERE ch.status_id = 1
            ) chhc ON chhc.customer_id = c.id AND chhc.rn = 1
            LEFT JOIN `user` u ON u.id = chhc.commercial_id
            LEFT JOIN `workshop_order` wo ON wo.image_command_id = ic.id
     
            LEFT JOIN `model` m ON m.id = cspec.model_id
            LEFT JOIN `carpet_collection` cc ON cc.id = cspec.collection_id
            LEFT JOIN `quality` q ON q.id = cspec.quality_id
            _WHERE_
            _GROUPBY_
            _ORDERBY_
        ';
    }

    private function getSelectStatement(): string
    {
        return '
        ic.id,
        ic.adv_comment AS adv,
        ic.rn,
        ic.studio_comment AS studio_comment,
        ic.command_number AS command_number,
        ic.created_at AS created_at,
        ic.updated_at AS updated_at,
        di.id AS di_id,
        di.demande_number AS di_number,
        cm.id AS contremarque_id,
        cm.designation AS contremarque,
        cc.id AS  carpet_collection_id,
        cc.reference AS collection_reference,
        q.id AS  quality_id,
        q.name AS  quality_name,
        m.id AS model_id,
        m.code AS model_code,
        
        MAX(CASE
            WHEN (cg.name != \'Particulier (Client)\') THEN CONCAT(c.social_reason, \'(\', c.code, \')\')
            ELSE CONCAT(ccis.firstname, \' \', ccis.lastname)
        END) AS customer,
        MAX(l.description) AS location,
        MAX(cs.name) AS status,
        MAX(CONCAT(u.firstname, \' \', u.lastname)) AS commercial,
        GROUP_CONCAT(DISTINCT d.id) AS designer_ids,
        cspec.id AS carpet_specification_id,
        (
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    "id", cd.id,
                    "mesurement_id", cd.mesurement_id,
                    "values", (
                        SELECT JSON_ARRAYAGG(
                            JSON_OBJECT(
                                "id", cdv.id,
                                "value", cdv.value,
                                "unit_id", cdv.unit_id
                            )
                        )
                        FROM carpet_dimension_value cdv
                        JOIN carpet_dimension_carpet_dimension_value cdcv ON cdcv.carpet_dimension_value_id = cdv.id
                        WHERE cdcv.carpet_dimension_id = cd.id
                    )
                )
            )
            FROM carpet_dimension cd
            WHERE cd.carpet_specification_id = cspec.id
        ) AS carpet_dimensions,
        (
        SELECT JSON_ARRAYAGG(
            JSON_OBJECT(
                "id", i.id,
                "image_reference", i.image_reference,
                "is_validated", i.is_validated,
                "has_error", i.has_error,
                "error", i.error,
                "commentaire", i.commentaire,
                "validated_at", i.validated_at,
                "image_type", it.name,
                "attachment", (
                    SELECT JSON_OBJECT(
                        "id", a.id,
                        "file", a.file,
                        "path", a.path,
                        "extension", a.extension,
                        "fromDistantServer", a.from_distant_server
                    )
                    FROM attachment a
                    JOIN image_attachment ia ON ia.attachment_id = a.id
                    WHERE ia.image_id = i.id
                    LIMIT 1
                )
            )
        )
        FROM image i
        LEFT JOIN image_type it ON it.id = i.image_type_id
        WHERE i.carpet_design_order_id = cdo.id
        AND it.name LIKE \'%Vignette%\'
    ) AS images
    ';
    }


    private function getWhereStatement(GetImageCommandQuery $query): string
    {
        $conditions = ['ic.canceled_by IS NULL'];

        if (null !== $query->designerId) {
            $conditions[] = 'd.id = :designerId';
        }
        if (null !== $query->customer) {
            $conditions[] = '(c.social_reason LIKE :customer OR CONCAT(ccis.firstname, \' \', ccis.lastname) LIKE :customer)';
        }
        if (null !== $query->contremarque) {
            $conditions[] = 'cm.designation LIKE :contremarque';
        }
        if (null !== $query->commercial) {
            $conditions[] = 'CONCAT(u.firstname, \' \', u.lastname) LIKE :commercial';
        }
        if (null !== $query->command) {
            $conditions[] = 'ic.command_number LIKE :command';
        }
        if (null !== $query->status) {
            $conditions[] = 'cs.name LIKE :status';
        }
        if (null !== $query->model) {
            $conditions[] = 'm.id = :model';
        }
        if (null !== $query->collection) {
            $conditions[] = 'cc.id = :collection';
        }
        if (null !== $query->quality) {
            $conditions[] = 'q.id = :quality';
        }
        if (null !== $query->location) {
            $conditions[] = 'l.description LIKE :location';
        }

        return count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getGroupByStatement(): string
    {
        return 'GROUP BY 
        ic.id, 
        ic.adv_comment, 
        ic.rn, 
        ic.studio_comment, 
        ic.command_number, 
        ic.created_at, 
        ic.updated_at,
        di.id,
        di.demande_number,
        cm.id,
        cm.designation,
        cspec.id ,
        cc.id,
        m.id,
        q.id';
    }

    private function getOrderStatement(GetImageCommandQuery $query): string
    {
        if (null === $query->orderBy || null === $query->orderWay) {
            return 'ORDER BY ic.created_at DESC';
        }

        $validColumns = [
            'id' => 'ic.id',
            'created_at' => 'ic.created_at',
            'customer' => 'customer',
            'contremarque' => 'cm.designation',
            'status' => 'cs.name',
            'commercial' => 'commercial',
            'command' => 'ic.command_number'
        ];

        if (array_key_exists($query->orderBy, $validColumns)) {
            return 'ORDER BY ' . $validColumns[$query->orderBy] . ' ' . strtoupper($query->orderWay);
        }

        return 'ORDER BY ic.created_at DESC';
    }

    private function bindQueryParameters($stmt, GetImageCommandQuery $query): void
    {
        $parameters = [
            ':designerId' => $query->designerId,
            ':customer' => $query->customer ? '%' . $query->customer . '%' : null,
            ':contremarque' => $query->contremarque ? '%' . $query->contremarque . '%' : null,
            ':commercial' => $query->commercial ? '%' . $query->commercial . '%' : null,
            ':command' => $query->command ? '%' . $query->command . '%' : null,
            ':status' => $query->status ? '%' . $query->status . '%' : null,
            ':location' => $query->location ? '%' . $query->location . '%' : null,
            ':quality' => $query->quality,
            ':model' => $query->model,
            ':collection' => $query->collection,
        ];
        foreach ($parameters as $key => $value) {
            if (null !== $value) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }

    private function transformResult(array $result, GetImageCommandQuery $query): array
    {
        $dimensions = [];
        if ($result['carpet_specification_id']) {
            $dimensions = $this->fetchDimensionsForSpecification($result['carpet_specification_id'], $query);
        }
        $images = [];
        if (!empty($result['images'])) {
            $images = json_decode($result['images'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $images = [];
            }
        }
        return [
            'id' => $result['id'],
            'adv' => $result['adv'],
            'Rn' => $result['rn'],
            'studio_comment' => $result['studio_comment'],
            'command_number' => $result['command_number'],
            'designer_ids' => $result['designer_ids'] ? array_map('intval', explode(',', $result['designer_ids'])) : [],
            'carpetDesignOrder' => [
                'di_id' => $result['di_id'],
                'di_number' => $result['di_number'],
                'contremarque_id' => $result['contremarque_id'],
                'contremarque' => $result['contremarque'],
                'customer' => $result['customer'],
                'location' => $result['location'],
                'status' => $result['status'],
                'commercial' => $result['commercial']
            ],
            'created_at' => $result['created_at'],
            'updated_at' => $result['updated_at'],
            'carpetSpecification' => [
                'id' => $result['carpet_specification_id'],
                'dimensions' => $dimensions,
                'modelId' => $result['model_id'],
                'model' => $result['model_code'],
                'collectionId' => $result['carpet_collection_id'],
                'collection' => $result['collection_reference'],
                'qualityId' => $result['quality_id'],
                'quality' => $result['quality_name'],
            ],
            'images' => $images,
        ];
    }

    private function getMaxResult(GetImageCommandQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT ic.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);

        $stmt = $this->imageCommandRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }

    private function fetchDimensionsForSpecification(
        int                  $specificationId,
        GetImageCommandQuery $query
    ): array
    {
        $sql = '
    SELECT 
        cd.id, 
        cd.mesurement_id,
        m.name AS measurement_name,
        cdv.id AS value_id, 
        cdv.value, 
        cdv.unit_id,
        u.name AS unit_name
    FROM carpet_dimension cd
    JOIN mesurement m ON m.id = cd.mesurement_id
    JOIN carpet_dimension_carpet_dimension_value cdcv ON cdcv.carpet_dimension_id = cd.id
    JOIN carpet_dimension_value cdv ON cdv.id = cdcv.carpet_dimension_value_id
    LEFT JOIN unit_of_measurement u ON u.id = cdv.unit_id
    WHERE cd.carpet_specification_id = :specId
    ';

        $params = ['specId' => $specificationId];
        $types = ['specId' => ParameterType::INTEGER];

        // Build conditions for measurements
        $measurementConditions = [];
        $measurementGroups = [];

        // First measurement filters
        if ($query->measurementName1 !== null) {
            $group = [];
            $group[] = 'm.name = :measurementName1';
            $params['measurementName1'] = $query->measurementName1;
            $types['measurementName1'] = ParameterType::STRING;

            if ($query->minDimensionValue1 !== null) {
                $group[] = 'cdv.value >= :minValue1';
                $params['minValue1'] = $query->minDimensionValue1;
                $types['minValue1'] = ParameterType::STRING;
            }

            if ($query->maxDimensionValue1 !== null) {
                $group[] = 'cdv.value <= :maxValue1';
                $params['maxValue1'] = $query->maxDimensionValue1;
                $types['maxValue1'] = ParameterType::STRING;
            }

            $measurementGroups[] = '(' . implode(' AND ', $group) . ')';
        }

        // Second measurement filters
        if ($query->measurementName2 !== null) {
            $group = [];
            $group[] = 'm.name = :measurementName2';
            $params['measurementName2'] = $query->measurementName2;
            $types['measurementName2'] = ParameterType::STRING;

            if ($query->minDimensionValue2 !== null) {
                $group[] = 'cdv.value >= :minValue2';
                $params['minValue2'] = $query->minDimensionValue2;
                $types['minValue2'] = ParameterType::STRING;
            }

            if ($query->maxDimensionValue2 !== null) {
                $group[] = 'cdv.value <= :maxValue2';
                $params['maxValue2'] = $query->maxDimensionValue2;
                $types['maxValue2'] = ParameterType::STRING;
            }

            $measurementGroups[] = '(' . implode(' AND ', $group) . ')';
        }

        // Add measurement conditions if any
        if (!empty($measurementGroups)) {
            $sql .= ' AND (' . implode(' OR ', $measurementGroups) . ')';
        }

        $stmt = $this->imageCommandRepository->getEntityManager()
            ->getConnection()
            ->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $types[$key]);
        }

        $results = $stmt->executeQuery()->fetchAllAssociative();

        $dimensions = [];
        foreach ($results as $row) {
            $dimId = $row['id'];
            if (!isset($dimensions[$dimId])) {
                $dimensions[$dimId] = [
                    'id' => $row['id'],
                    'measurement_id' => $row['mesurement_id'],
                    'measurement_name' => $row['measurement_name'],
                    'values' => []
                ];
            }

            $dimensions[$dimId]['values'][] = [
                'id' => $row['value_id'],
                'value' => $row['value'],
                'unit_id' => $row['unit_id'],
                'unit_name' => $row['unit_name']
            ];
        }

        return array_values($dimensions);
    }
}