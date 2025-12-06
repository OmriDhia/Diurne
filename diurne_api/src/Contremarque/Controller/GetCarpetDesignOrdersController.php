<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetDesignOrders\GetCarpetDesignOrdersQuery;
use App\Contremarque\DTO\GetCarpetDesignOrdersQueryDto;
use App\Contremarque\Entity\CarpetDesignOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetCarpetDesignOrdersController extends CommandQueryController
{
    #[Route('/api/carpetDesignOrders/all', name: 'get_all_carpetDesignOrders', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get carpetDesignOrders',
        content: new Model(type: CarpetDesignOrder::class)
    )]
    #[OA\RequestBody(
        description: 'CarpetDesignOrder data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemsPerPage', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapQueryString] GetCarpetDesignOrdersQueryDto $query,
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started
        $currentUser = $session->get('user');

        $designerId = null !== $query->filter ? $query->filter->getDesignerId() : null;

        $getCarpetDesignOrders = new GetCarpetDesignOrdersQuery(
            (int) $currentUser->getId(),
            $query->page ?? null,
            $query->itemsPerPage ?? null,
            $query->orderBy ?? null,
            $query->orderWay ?? null,

            $designerId,

            $query->filter->prescripteur ?? null,
            $query->filter->customer ?? null,
            $query->filter->diNumber ?? null,
            $query->filter->diId ?? null,
            $query->filter->contremarque ?? null,
            $query->filter->statusId ?? null,
            $query->filter->maquette ?? null,
            $query->filter->cmdAtelier ?? null,
            $query->filter->collectionId ?? null,
            $query->filter->modelId ?? null,
            $query->filter->contremarqueId ?? null
        );

        $response = $this->ask($getCarpetDesignOrders);

        return SuccessResponse::create(
            'get_all_carpetDesignOrders',
            $response
        );
    }
}
