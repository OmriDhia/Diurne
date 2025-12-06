<?php

// src/Contremarque/Controller/GetContremarqueListController.php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Query\Contremarque\GetContremarqueListQuery;
use App\Contremarque\DTO\GetContremarqueListRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class GetContremarqueListController extends CommandQueryController
{
    #[Route('/api/contremarques', name: 'contremarque_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Contremarque listing',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Contremarque')
        )
    )]
    #[OA\Parameter(
        name: 'designation',
        in: 'query',
        description: 'Filter by designation',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'customerId',
        in: 'query',
        description: 'Filter by customer ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'commercialId',
        in: 'query',
        description: 'Filter by commercial ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'commercial',
        in: 'query',
        description: 'Filter by commercial',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'prescripteur',
        in: 'query',
        description: 'Filter by prescripteur',
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
        name: 'targetDate',
        in: 'query',
        description: 'Filter by target date',
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
    #[OA\Parameter(
        name: 'customerName',
        in: 'query',
        description: 'Filter by customer name',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'commercialName',
        in: 'query',
        description: 'Filter by commercial name',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapQueryString] GetContremarqueListRequestDto $dto
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }
        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started
        $currentUser = $session->get('user');
        $query = new GetContremarqueListQuery(
            (int) $dto->page,
            (int) $dto->itemsPerPage,
            $dto->orderWay,
            $dto->designation,
            $dto->customerId,
            $dto->contremarqueId,
            $dto->commercialId,
            $dto->creationDate,
            $dto->targetDate,
            (int) $dto->limit,
            $dto->order,
            $dto->customerName,
            $dto->commercial,
            $dto->prescripteur,
            $dto->relaunchExceeded,
            $dto->relaunchExceededByWeek,
            $dto->withoutRelaunch,
            $dto->isCurrentProject,
            $currentUser->getId()
        );
        $response = $this->ask($query);

        return new JsonResponse($response->toArray(), 200);
    }
}
