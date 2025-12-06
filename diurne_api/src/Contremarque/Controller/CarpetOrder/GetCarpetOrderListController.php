<?php

namespace App\Contremarque\Controller\CarpetOrder;

use App\Common\Controller\CommandQueryController;

use App\Contremarque\Bus\Query\GetCarpetOrder\GetCarpetOrderListQuery;
use App\Contremarque\DTO\CarpetOrder\GetCarpetOrderListRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetOrderListController extends CommandQueryController
{
    #[Route('/api/carpetOrders', name: 'carpet_order_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Carpet order listing',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/CarpetOrder')
        )
    )]
    #[OA\Parameter(
        name: 'reference',
        in: 'query',
        description: 'Filter by reference',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'original_quote_reference',
        in: 'query',
        description: 'Filter by original quote reference',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'contremarque',
        in: 'query',
        description: 'Filter by contremarque',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'customer',
        in: 'query',
        description: 'Filter by customer',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'creationDate',
        in: 'query',
        description: 'Filter by creation date',
        required: false,
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Page number',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Items per page',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'order',
        in: 'query',
        description: 'Order by field',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'orderWay',
        in: 'query',
        description: 'Order way (ASC or DESC)',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'])
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(
        #[MapQueryString] GetCarpetOrderListRequestDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $session = $this->container->get('request_stack')->getSession();
        $session->start();
        $currentUser = $session->get('user');

        $query = new GetCarpetOrderListQuery(
            (int)$dto->page,
            (int)$dto->itemsPerPage,
            $dto->orderBy,
            $dto->orderWay,
            $dto->reference,
            $dto->originalQuoteReference,
            $dto->contremarque,
            $dto->contremarqueId,
            $dto->customer,
            $dto->commercial,
            $dto->creationDate,
            $currentUser->getId()
        );

        $response = $this->ask($query);

        return new JsonResponse($response->toArray(), 200);
    }
}