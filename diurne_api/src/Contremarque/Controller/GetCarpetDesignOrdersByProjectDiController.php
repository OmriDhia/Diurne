<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetDesignOrdersByProjectDi\GetCarpetDesignOrdersByProjectDiQuery;
use App\Contremarque\Bus\Query\GetCarpetDesignOrdersByProjectDi\GetCarpetDesignOrdersByProjectDiResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetDesignOrdersByProjectDiController extends CommandQueryController
{
    #[Route('/api/contremarque/{contremarqueId}/projectDi/{projectDiId}/carpetDesignOrders', name: 'get_carpet_design_orders_by_project_di', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get carpetDesignOrders by projectDi and contremarque',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'projectDi', type: 'integer'),
                new OA\Property(property: 'location', type: 'integer'),
                new OA\Property(property: 'status', type: 'integer'),
                new OA\Property(property: 'designers', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'carpetSpecification', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $contremarqueId, int $projectDiId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GetCarpetDesignOrdersByProjectDiQuery($contremarqueId, $projectDiId);
        /** @var GetCarpetDesignOrdersByProjectDiResponse $response */
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_carpet_design_orders_by_project_di',
            $response->toArray()
        );
    }
}
