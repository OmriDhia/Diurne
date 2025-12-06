<?php

namespace App\Contremarque\Controller\OrderPaymentDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\OrderPaymentDetail\OrderPaymentDetailResponse;
use App\Contremarque\DTO\OrderPayment\UpdateOrderPaymentDetailRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateOrderPaymentDetailController extends CommandQueryController
{
    #[Route(
        '/api/order-payment-details/{id}/update',
        name: 'update_order_payment_detail',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 201,
        description: 'Update Order Payment Detail',
        content: new Model(type: OrderPaymentDetailResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'orderPaymentId', type: 'integer'),
                new OA\Property(property: 'quoteId', type: 'integer', nullable: true),
                new OA\Property(property: 'quoteDetailId', type: 'integer', nullable: true),
                new OA\Property(property: 'customerInvoiceId', type: 'integer', nullable: true),
                new OA\Property(property: 'customerInvoiceDetailId', type: 'integer', nullable: true),
                new OA\Property(property: 'commandNumber', type: 'string', nullable: true),
                new OA\Property(property: 'orderInvoiceId', type: 'integer', nullable: true),
                new OA\Property(property: 'rn', type: 'string', maxLength: 10),
                new OA\Property(property: 'distribution', type: 'string', example: '0'),
                new OA\Property(property: 'allocatedAmountTtc', type: 'string', example: '0'),
                new OA\Property(property: 'remainingAmountTtc', type: 'string', example: '0'),
                new OA\Property(property: 'totalAmountTtc', type: 'string', example: '0'),
                new OA\Property(property: 'tva', type: 'string', example: '0'),
                new OA\Property(property: 'allocatedAmountHt', type: 'string', example: '0'),
                new OA\Property(property: 'cleared', type: 'boolean', example: false),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int                                                     $id,
        #[MapRequestPayload] UpdateOrderPaymentDetailRequestDto $requestDto,
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = $requestDto->toUpdateOrderPaymentDetailCommand($id);

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