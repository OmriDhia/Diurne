<?php

namespace App\Contremarque\Controller\OrderPayment;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\OrderPayment\CreateOrderPaymentCommand;
use App\Contremarque\Bus\Command\OrderPayment\OrderPaymentResponse;
use App\Contremarque\DTO\OrderPayment\CreateOrderPaymentRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/order-payment', name: 'create_order_payment', methods: ['POST'])]
class CreateOrderPaymentController extends CommandQueryController
{
    #[OA\Response(
        response: 201,
        description: 'Create Order Payment',
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
        #[MapRequestPayload] CreateOrderPaymentRequestDto $requestDto,
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'Contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new CreateOrderPaymentCommand(
            paymentMethodId: $requestDto->paymentMethodId,
            customerId: $requestDto->customerId,
            commercialId: $requestDto->commercialId,
            currencyId: $requestDto->currencyId,
            taxRuleId: $requestDto->taxRuleId,
            accountLabel: $requestDto->accountLabel,
            transactionNumber: $requestDto->transactionNumber,
            paymentAmountHt: $requestDto->paymentAmountHt,
            paymentAmountTtc: $requestDto->paymentAmountTtc,
        );

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
