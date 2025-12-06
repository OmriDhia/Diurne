<?php

namespace App\Workshop\Controller\HistoryEventTypeCategory;

use App\Common\Controller\CommandQueryController;

use App\Workshop\Bus\Query\GetHistoryEventTypeCategory\GetHistoryEventTypeCategoryQuery;
use App\Workshop\Entity\HistoryEventTypeCategory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Common\Response\SuccessResponse;

class GetHistoryEventTypeCategoryListController extends CommandQueryController
{
    #[Route('/api/historyEventTypeCategory', name: 'history_event_type_categories_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of history event type categories',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: HistoryEventTypeCategory::class))),
                new OA\Property(property: 'meta', properties: [
                    new OA\Property(property: 'total', type: 'integer'),
                    new OA\Property(property: 'pages', type: 'integer')
                ], type: 'object')
            ]
        )
    )]
    #[OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'itemsPerPage', in: 'query', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        $query = new GetHistoryEventTypeCategoryQuery();

        $response = $this->ask($query);

        return SuccessResponse::create(
            'history_event_type_categories_list',
            $response->toArray()
        );
    }
}