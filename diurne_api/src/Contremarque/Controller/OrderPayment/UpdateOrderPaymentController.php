<?php

namespace App\Contremarque\Controller\OrderPayment;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\OrderPayment\OrderPaymentResponse;
use App\Contremarque\DTO\OrderPayment\UpdateOrderPaymentRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateOrderPaymentController extends CommandQueryController
{
    #[Route(
        '/api/order-payments/{id}/update',
        name: 'update_order_payment',
        methods: ['PUT'],
        requirements: ['id' => '\d+']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'The ID of the order payment to update',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Update Order Payment',
        content: new Model(type: OrderPaymentResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'paymentMethodId', type: 'integer'),
                new OA\Property(property: 'customerId', type: 'integer', nullable: true),
                new OA\Property(property: 'commercialId', type: 'integer', nullable: true),
                new OA\Property(property: 'currencyId', type: 'integer'),
                new OA\Property(property: 'taxRuleId', type: 'integer'),
                new OA\Property(property: 'accountLabel', type: 'string', nullable: true),
                new OA\Property(property: 'transactionNumber', type: 'string', maxLength: 128, nullable: true),
                new OA\Property(property: 'paymentAmountHt', type: 'string', example: '1000.500000'),
                new OA\Property(property: 'paymentAmountTtc', type: 'string', example: '1200.600000'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] UpdateOrderPaymentRequestDto $requestDto,
        int                                               $id
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = $requestDto->toUpdateOrderPaymentCommand($id);

        try {
            $response = $this->handle($command);
            return SuccessResponse::create(
                'order_payment_updated',
                $response->toArray(),
                'Order payment updated successfully',
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