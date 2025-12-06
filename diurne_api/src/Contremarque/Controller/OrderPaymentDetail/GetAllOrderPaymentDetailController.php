<?php

namespace App\Contremarque\Controller\OrderPaymentDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\OrderPaimentDetails\GetAllOrderPaymentDetailsQuery;
use App\Contremarque\Bus\Query\OrderPaimentDetails\OrderPaymentDetailsQueryResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAllOrderPaymentDetailController extends CommandQueryController
{
    #[Route(
        '/api/order-payment-details',
        name: 'get_all_order_payment_details',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Retrieve all order payment details',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'key', type: 'string', example: 'order_payment_details_retrieved'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: OrderPaymentDetailsQueryResponse::class))
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Order payment details retrieved successfully')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllOrderPaymentDetailsQuery();
        $result = $this->ask($query);

        return SuccessResponse::create(
            'order_payment_details_retrieved',
            $result->toArray(),
            'Order payment details retrieved successfully',
            200
        );
    }
}