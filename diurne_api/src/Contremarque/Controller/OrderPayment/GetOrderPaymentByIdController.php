<?php

namespace App\Contremarque\Controller\OrderPayment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetOrderPaymentById\GetOrderPaymentByIdQuery;
use App\Contremarque\Bus\Query\GetOrderPaymentById\GetOrderPaymentByIdQueryResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetOrderPaymentByIdController extends CommandQueryController
{
    #[Route(
        '/api/order-payment/{id}',
        name: 'get_order_payment_by_id',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Retrieve order payment by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'key', type: 'string', example: 'order_payment_retrieved'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: GetOrderPaymentByIdQueryResponse::class))
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Order payment retrieved successfully')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetOrderPaymentByIdQuery($id);
        $result = $this->ask($query);

        return SuccessResponse::create(
            'order_payment_retrieved',
            $result->toArray(),
            'Order payments retrieved successfully',
            200
        );
    }
}