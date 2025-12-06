<?php

namespace App\Contremarque\Controller\OrderPaymentDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetOrderPaymentDetailById\GetOrderPaymentDetailByIdQuery;
use App\Contremarque\Bus\Query\GetOrderPaymentDetailById\GetOrderPaymentDetailByIdQueryResponse;
use App\Contremarque\Bus\Query\OrderPaimentDetails\GetAllOrderPaymentDetailsQuery;
use App\Contremarque\Bus\Query\OrderPaimentDetails\OrderPaymentDetailsQueryResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetOrderPaymentDetailByIdController extends CommandQueryController
{
    #[Route(
        '/api/order-payment-detail/{id}',
        name: 'get_order_payment_detail_by_id',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Retrieve  order payment detail',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'key', type: 'string', example: 'order_payment_detail_retrieved'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: GetOrderPaymentDetailByIdQueryResponse::class))
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Order payment detail retrieved successfully')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetOrderPaymentDetailByIdQuery($id);
        $result = $this->ask($query);

        return SuccessResponse::create(
            'order_payment_detail_retrieved',
            $result->toArray(),
            'Order payment details retrieved successfully',
            200
        );
    }
}