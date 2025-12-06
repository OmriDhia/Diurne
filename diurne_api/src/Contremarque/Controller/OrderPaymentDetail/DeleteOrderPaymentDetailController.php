<?php

namespace App\Contremarque\Controller\OrderPaymentDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\OrderPaymentDetail\DeleteOrderPaymentDetailCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class DeleteOrderPaymentDetailController extends CommandQueryController
{
    #[Route(
        '/api/order-payment-details/{id}',
        name: 'delete_order_details_payment',
        methods: ['DELETE'],
        requirements: ['id' => '\d+']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The ID of the order payment details to delete',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Delete Order Payment Details',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'key', type: 'string', example: 'order_payment_details_deleted'),
                new OA\Property(property: 'data', type: 'object', nullable: true),
                new OA\Property(property: 'message', type: 'string', example: 'Order payment details deleted successfully')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id
    ): JsonResponse
    {
        if (!$this->isGranted('delete', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new DeleteOrderPaymentDetailCommand($id);

        try {
            $response = $this->handle($command);
            return SuccessResponse::create(
                'order_payment_details_deleted',
                $response->toArray(),
                'Order payment details deleted successfully',
                200
            );
        } catch (RuntimeException $e) {
            return new JsonResponse([
                'code' => 400,
                'message' => 'Validation errors',
                'errors' => [$e->getMessage()]
            ], 400);
        }
    }
}