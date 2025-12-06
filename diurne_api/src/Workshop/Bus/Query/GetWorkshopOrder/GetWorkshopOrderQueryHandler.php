<?php

namespace App\Workshop\Bus\Query\GetWorkshopOrder;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopOrderRepository;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use RuntimeException;

final class GetWorkshopOrderQueryHandler implements QueryHandler
{

    public function __construct(
        private readonly WorkshopOrderRepository $workshopOrderRepository,

    )
    {
    }

    public function __invoke(GetWorkshopOrderQuery $query): GetWorkshopOrderQueryResponse
    {

        try {
            $stmt = $this->prepareQuery($query);
            $results = $stmt->executeQuery()->fetchAllAssociative();

            $workshopOrders = array_map(
                fn(array $result) => $this->transformResult($result),
                $results
            );

            return new GetWorkshopOrderQueryResponse(
                $workshopOrders,
                $this->getMaxResult($query),
                (int)ceil($this->getMaxResult($query) / $query->itemsPerPage)
            );
        } catch (Exception $e) {
            throw new RuntimeException('Failed to execute query: ' . $e->getMessage());
        }
    }

    private function prepareQuery(GetWorkshopOrderQuery $query)
    {
        $sql = $this->buildSqlQuery($query);
        $stmt = $this->workshopOrderRepository->getEntityManager()->getConnection()->prepare($sql);

        $this->bindQueryParameters($stmt, $query);
        $stmt->bindValue(':itemsPerPage', $query->itemsPerPage, ParameterType::INTEGER);
        $stmt->bindValue(':offset', $query->itemsPerPage * ($query->page - 1), ParameterType::INTEGER);

        return $stmt;
    }

    private function buildSqlQuery(GetWorkshopOrderQuery $query): string
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
        FROM `workshop_order` wo
        LEFT JOIN `image_command` ic ON ic.id = wo.image_command_id
        LEFT JOIN `workshop_information` wf ON wf.id = wo.workshop_information_id
        LEFT JOIN `carpet_design_order` cdo ON cdo.id = ic.carpet_design_order_id
        LEFT JOIN `carpet_specification` cspec ON cspec.id = cdo.carpet_specification_id
        LEFT JOIN `model` m ON m.id = cspec.model_id
        LEFT JOIN `carpet_collection` cc ON cc.id = m.carpet_collection_id
        LEFT JOIN `project_di` di ON di.id = cdo.project_di_id
        LEFT JOIN `contremarque` contremarque ON di.contremarque_id = contremarque.id
        LEFT JOIN `customer` c ON contremarque.customer_id = c.id
        LEFT JOIN (
            SELECT customer_id, id AS last_id, commercial_id,
                   ROW_NUMBER() OVER (PARTITION BY customer_id ORDER BY id DESC) AS rn
            FROM contact_commercial_history
            WHERE status_id = 1
        ) chhc ON chhc.customer_id = c.id AND chhc.rn = 1
        LEFT JOIN user us ON chhc.commercial_id = us.id
        LEFT JOIN `customer_group` cg ON cg.id = c.customer_group_id
        LEFT JOIN `contact_information_sheet` ccis ON ccis.id = c.contact_information_sheet_id
        LEFT JOIN `location` l ON cdo.location_id = l.id
        LEFT JOIN `carpet_status` cs ON cs.id = cdo.status_id
        LEFT JOIN `workshop_image` wi ON wi.workshop_order_id = wo.id
        LEFT JOIN `attachment` a ON a.id = wi.id_attachment
        LEFT JOIN `designer_assignment` da ON da.carpet_design_order_id = cdo.id
            AND da.date_from = (
                SELECT MAX(date_from)
                FROM `designer_assignment` da_sub
                WHERE da_sub.carpet_design_order_id = cdo.id
            )
        LEFT JOIN `user` u ON u.id = da.designer_id
        LEFT JOIN (
            SELECT pc.workshop_order_id, MAX(pc.id) AS provisional_calendar_id
            FROM provisional_calendar pc
            GROUP BY pc.workshop_order_id
        ) pci ON pci.workshop_order_id = wo.id
        _WHERE_
        _GROUPBY_
        _ORDERBY_
    ';
    }

    private function getSelectStatement(): string
    {
        return '
            wo.id,
            wo.reference,
            cc.reference AS collection_reference,
            m.code AS model_code,
            wo.created_at AS creatDate,
            wo.updated_at AS updateDate,
            ic.id AS image_command_id,
            wf.id AS workshop_information_id,
            wf.rn AS Rns,
            ic.commercial_comment,
            ic.adv_comment,
            ic.rn,
            ic.command_number,
            ic.studio_comment,
            ic.created_at AS image_command_created_at,
            ic.updated_at AS image_command_updated_at,
            di.id AS di_id,
            chhc.commercial_id,
            
            CONCAT_WS(" ", us.firstname, us.lastname) AS commercial,
            di.demande_number AS diNumber,
            contremarque.id AS contremarque_id,
            contremarque.designation AS contremarque,
            CASE
                WHEN (cg.name != \'Particulier (Client)\') THEN CONCAT(c.social_reason, \'(\', c.code, \' )\')
                ELSE CONCAT(ccis.firstname, \' \' , ccis.lastname)
            END AS customer,
            l.description AS location,
            cs.name AS status,
            CONCAT(u.firstname, \' \' , u.lastname) AS designer,
            (
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    "id", wi.id,
                    "file_name", wi.file_name,
                    "format", wi.format,
                    "attachment_id", a.id,
                    "attachment_path", a.path,
                    "attachment_file", a.file
                )
            )
            FROM workshop_image wi
            LEFT JOIN attachment a ON a.id = wi.id_attachment
            WHERE wi.workshop_order_id = wo.id
        ) AS workshop_images,
        pci.provisional_calendar_id AS provisional_calendar_id
        ,(
            SELECT JSON_OBJECT(
                \'id\', (
                    SELECT pr2.id FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'datePR\', (
                    SELECT pr2.date_pr FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'author\', (
                    SELECT pr2.author_id FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'comment\', (
                    SELECT pr2.comment FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'dateEvent\', (
                    SELECT pr2.date_event FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'dateWorkshop\', (
                    SELECT pr2.date_workshop FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'tissage\', (
                    SELECT pr2.tissage FROM progress_report pr2
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                ),
                \'status\', (
                    SELECT JSON_OBJECT(\'id\', prs.id, \'status\', prs.status)
                    FROM progress_report pr2
                    LEFT JOIN progress_report_status prs ON prs.id = pr2.status_id
                    LEFT JOIN provisional_calendar pc2 ON pr2.provisional_calendar_id = pc2.id
                    WHERE pc2.workshop_order_id = wo.id
                    ORDER BY pr2.id DESC LIMIT 1
                )
            )
        ) AS last_progress_report
        ,(
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    \'carpetOrderDetailId\', cod.id,
                    \'quoteDetailId\', qd.id,
                    \'quoteDetailReference\', qd.reference,
                    \'rnAttributionId\', r.id,
                    \'carpetOrderId\', co.id,
                    \'originalQuoteId\', q1.id,
                    \'originalQuoteReference\', q1.reference,
                    \'clonedQuoteId\', q2.id,
                    \'clonedQuoteReference\', q2.reference
                )
            )
            FROM rn_attribution r
            LEFT JOIN carpet c ON c.id = r.carpet_id
            LEFT JOIN carpet_order_detail cod ON cod.id = r.carpet_order_detail_id
            LEFT JOIN carpet_order co ON co.id = cod.carpet_order_id
            LEFT JOIN quote q2 ON q2.id = co.cloned_quote_id
            LEFT JOIN quote q1 ON q1.id = co.original_quote_id
            LEFT JOIN quote_detail qd ON qd.id = cod.quote_detail_id
            WHERE c.workshop_order_id = wo.id
        ) AS rn_carpet_order_details
    ';
    }

    private function getWhereStatement(GetWorkshopOrderQuery $query): string
    {
        $conditions = [];

        if (null !== $query->customer) {
            $conditions[] = '(c.social_reason LIKE :customer OR CONCAT(ccis.firstname, \' \' , ccis.lastname) LIKE :customer)';
        }
        if (null !== $query->contremarque) {
            $conditions[] = 'contremarque.designation LIKE :contremarque';
        }
        if (null !== $query->reference) {
            $conditions[] = 'wo.reference LIKE :reference';
        }
        if (null !== $query->statusId) {
            $conditions[] = 'cs.id = :statusId';
        }
        if (null !== $query->designerId) {
            $conditions[] = 'da.designer_id = :designerId';
        }
        if (null !== $query->commercial) {
            $conditions[] = 'CONCAT_WS(" ", us.firstname, us.lastname) LIKE :commercial';
        }
        if (null !== $query->collection) {
            $conditions[] = 'cc.reference LIKE :collection_reference';
        }
        if (null !== $query->model) {
            $conditions[] = 'm.code LIKE :model_code';
        }
        if (null !== $query->rn) {
            $conditions[] = 'wf.rn LIKE :rn';
        }
        if (null !== $query->location) {
            $conditions[] = 'l.description LIKE :location';
        }
        return count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    private function getGroupByStatement(): string
    {
        return 'GROUP BY wo.id, cc.reference, m.code, wo.reference, wo.created_at, wo.updated_at, 
            ic.id, wf.id, ic.commercial_comment, ic.adv_comment, ic.rn, ic.command_number, 
            ic.studio_comment, ic.created_at, ic.updated_at, di.id, chhc.commercial_id, 
            us.firstname, us.lastname, di.demande_number, contremarque.id, 
            contremarque.designation, c.social_reason, c.code, ccis.firstname, 
            ccis.lastname, l.description, cs.name, u.firstname, u.lastname, pci.provisional_calendar_id';
    }

    private function getOrderStatement(GetWorkshopOrderQuery $query): string
    {
        if (null === $query->orderBy || null === $query->orderWay) {
            return 'ORDER BY wo.created_at DESC';
        }

        $validColumns = [
            'reference' => 'wo.reference',
            'creatDate' => 'wo.created_at',
            'customer' => 'c.id',
            'contremarque' => 'contremarque.designation',
            'status' => 'cs.name',
            'designer' => 'CONCAT(u.firstname, \' \', u.lastname)'
        ];

        if (
            array_key_exists($query->orderBy, $validColumns)
            && in_array(strtoupper($query->orderWay), ['ASC', 'DESC'])
        ) {
            return 'ORDER BY ' . $validColumns[$query->orderBy] . ' ' . strtoupper($query->orderWay);
        }

        return 'ORDER BY wo.created_at DESC';
    }

    private function bindQueryParameters($stmt, GetWorkshopOrderQuery $query): void
    {
        $parameters = [
            ':customer' => $query->customer ? '%' . $query->customer . '%' : null,
            ':contremarque' => $query->contremarque ? '%' . $query->contremarque . '%' : null,
            ':reference' => $query->reference ? '%' . $query->reference . '%' : null,
            ':statusId' => $query->statusId,
            ':designerId' => $query->designerId,
            ':commercial' => $query->commercial ? '%' . $query->commercial . '%' : null,
            ':collection_reference' => $query->collection ? '%' . $query->collection . '%' : null,
            ':model_code' => $query->model ? '%' . $query->model . '%' : null,
            ':rn' => $query->rn ? '%' . $query->rn . '%' : null,
            ':location' => $query->location ? '%' . $query->location . '%' : null,

        ];

        foreach ($parameters as $key => $value) {
            if (null !== $value) {
                $stmt->bindValue($key, $value, is_int($value) ? ParameterType::INTEGER : ParameterType::STRING);
            }
        }
    }

    private function transformResult(array $result): array
    {
        $workshopImages = [];
        if (!empty($result['workshop_images'])) {
            $images = json_decode($result['workshop_images'], true);
            if (is_array($images)) {
                foreach ($images as $imageData) {
                    $workshopImages[] = [
                        'id' => $imageData['id'],
                        'file_name' => $imageData['file_name'],
                        'format' => $imageData['format'],
                        'attachment' => [
                            'id' => $imageData['attachment_id'],
                            'path' => $imageData['attachment_path'],
                            'file' => $imageData['attachment_file']
                        ]
                    ];
                }
            }
        }
        $progressReports = [];
        if (!empty($result['last_progress_report'])) {
            $reportData = json_decode($result['last_progress_report'], true);
            if (is_array($reportData)) {
                $progressReports = [
                    'id' => $reportData['id'],
                    'datePR' => $reportData['datePR'],
                    'author' => $reportData['author'],
                    'comment' => $reportData['comment'],
                    'dateEvent' => $reportData['dateEvent'],
                    'dateWorkshop' => $reportData['dateWorkshop'],
                    'tissage' => $reportData['tissage'],
                    'status' => $reportData['status'],
                ];
            }
        }
        $rnCarpetOrderDetails = [];
        if (!empty($result['rn_carpet_order_details'])) {
            $rnDetails = json_decode($result['rn_carpet_order_details'], true);
            if (is_array($rnDetails)) {
                foreach ($rnDetails as $detail) {
                    $rnCarpetOrderDetails[] = [
                        'carpetOrderDetailId' => $detail['carpetOrderDetailId'] ?? null,
                        'quoteDetailId' => $detail['quoteDetailId'] ?? null,
                        'quoteDetailReference' => $detail['quoteDetailReference'] ?? null,
                        'rnAttributionId' => $detail['rnAttributionId'] ?? null,
                        'carpetOrderId' => $detail['carpetOrderId'] ?? null,
                        'originalQuoteId' => $detail['originalQuoteId'] ?? null,
                        'originalQuoteReference' => $detail['originalQuoteReference'] ?? null,
                        'clonedQuoteId' => $detail['clonedQuoteId'] ?? null,
                        'clonedQuoteReference' => $detail['clonedQuoteReference'] ?? null,
                    ];
                }
            }
        }
        return [
            'id' => $result['id'],
            'reference' => $result['reference'],
            'creatDate' => $result['creatDate'],
            'updateDate' => $result['updateDate'],
            'imageCommand' => [
                'id' => $result['image_command_id'],
                'commercialComment' => $result['commercial_comment'],
                'advComment' => $result['adv_comment'],
                'rn' => $result['rn'],
                'commandNumber' => $result['command_number'],
                'studioComment' => $result['studio_comment'],
                'createdAt' => $result['image_command_created_at'],
                'updatedAt' => $result['image_command_updated_at'],
            ],
            'carpetDesignOrder' => [
                'di_id' => $result['di_id'],
                'diNumber' => $result['diNumber'],
                'contremarque_id' => $result['contremarque_id'],
                'contremarque' => $result['contremarque'],
                'customer' => $result['customer'],
                'location' => $result['location'],
                'status' => $result['status'],
                'designer' => $result['designer']
            ],
            'workshopImages' => $workshopImages,
            'workshopInfo' => [
                'id' => $result['workshop_information_id'],
                'rn' => $result['Rns']
            ],
            'Commercial' => [
                'id' => $result['commercial_id'],
                'commercial' => $result['commercial'],
            ],
            'carpetSpecification' => [
                'collection_reference' => $result['collection_reference'],
                'model_code' => $result['model_code'],
                // ... other carpet_specification fields ...
            ],
            'provisionalCalendarId' => $result['provisional_calendar_id'] ?? null,
            'lastProgressReport' => !empty($progressReports) ? $progressReports : null,
            'rnCarpetOrderDetails' => !empty($rnCarpetOrderDetails) ? $rnCarpetOrderDetails : null,
        ];
    }

    private function getMaxResult(GetWorkshopOrderQuery $query): int
    {
        $sql = str_replace('_SELECT_', 'COUNT(DISTINCT wo.id)', $this->getQueryBody());
        $sql = str_replace('_WHERE_', $this->getWhereStatement($query), $sql);
        $sql = str_replace('_ORDERBY_', '', $sql);
        $sql = str_replace('_GROUPBY_', '', $sql);

        $stmt = $this->workshopOrderRepository->getEntityManager()->getConnection()->prepare($sql);
        $this->bindQueryParameters($stmt, $query);

        return (int)$stmt->executeQuery()->fetchOne();
    }
}
