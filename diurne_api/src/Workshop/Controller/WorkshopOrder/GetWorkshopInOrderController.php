<?php

namespace App\Workshop\Controller\WorkshopOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopOrder\GetWorkshopOrderQuery;

use App\Workshop\DTO\WorkshopOrder\GetWorkshopOrderQueryDto;
use App\Workshop\Entity\WorkshopOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class GetWorkshopInOrderController extends CommandQueryController
{
    #[Route('/api/workshopOrders', name: 'workshop_order_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of workshop order',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: WorkshopOrder::class))
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
    #[OA\Parameter(
        name: 'page',
        description: 'Page number',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'itemsPerPage',
        description: 'Items per page',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 10)
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapQueryString] GetWorkshopOrderQueryDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetWorkshopOrderQuery(
            page: $requestDto->page,
            itemsPerPage: $requestDto->itemsPerPage,
            filters: $requestDto->filters,
            orderBy: $requestDto->orderBy,
            customer: $requestDto->customer,
            contremarque: $requestDto->contremarque,
            reference: $requestDto->reference,
            commercial: $requestDto->commercial,
            collection: $requestDto->collection,
            model: $requestDto->model,
            rn: $requestDto->rn,
            location: $requestDto->location

        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'workshop_order_list',
            $response
        );
    }
}