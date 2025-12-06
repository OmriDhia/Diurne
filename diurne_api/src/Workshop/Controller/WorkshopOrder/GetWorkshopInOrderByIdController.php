<?php

namespace App\Workshop\Controller\WorkshopOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopOrder\GetWorkshopOrderQuery;
use App\Workshop\Bus\Query\GetWorkshopOrderById\GetWorkshopOrderByIdQuery;
use App\Workshop\DTO\WorkshopOrder\GetWorkshopOrderQueryDto;
use App\Workshop\Entity\WorkshopOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class GetWorkshopInOrderByIdController extends CommandQueryController
{
    #[Route('/api/workshopOrders/{id}', name: 'workshop_order_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns single workshop order',
        content: new Model(type: WorkshopOrder::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop order not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop order ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetWorkshopOrderByIdQuery($id);

        $response = $this->ask($query);

        return SuccessResponse::create(
            'workshop_order_list',
            $response->toArray()
        );
    }
}