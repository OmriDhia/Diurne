<?php

namespace App\Workshop\Controller\HistoryEventCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetHistoryEventCategory\GetHistoryEventCategoryQuery;
use App\Workshop\Bus\Query\GetHistoryEventTypes\GetHistoryEventTypesQuery;
use App\Workshop\DTO\HistoryEventCategory\GetHistoryEventCategoryDto;
use App\Workshop\DTO\HistoryEventTypes\GetHistoryEventTypesDto;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetHistoryEventCategoryListController extends CommandQueryController
{
    #[Route('/api/historyEventCategory', name: 'history_event_category_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of history event category',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: HistoryEventCategory::class))
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
        $query = new GetHistoryEventCategoryQuery();

        $response = $this->ask($query);
        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event category not found'],
                404
            );
        }
        return SuccessResponse::create(
            'history_event_category_list',
            $response->toArray()
        );
    }
}