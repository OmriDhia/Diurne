<?php

namespace App\Workshop\Controller\WorkshopRnHistory;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;


use App\Workshop\Bus\Query\GetWorkshopRnHistory\GetWorkshopRnHistoryQuery;
use App\Workshop\DTO\HistoryEventCategory\GetHistoryEventCategoryDto;
use App\Workshop\Entity\WorkshopRnHistory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopRnHistoryListController extends CommandQueryController
{
    #[Route('/api/workshopRnHistories', name: 'workshop_rn_histories_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of workshop RN histories',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: WorkshopRnHistory::class))
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer'),
                        new OA\Property(property: 'pages', type: 'integer')
                    ],
                    type: 'object'
                )
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }
        $query = new GetWorkshopRnHistoryQuery();
        $response = $this->ask($query);
        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event category not found'],
                404
            );
        }
        return SuccessResponse::create(
            'workshop_rn_histories_list',
            $response->toArray()
        );
    }
}