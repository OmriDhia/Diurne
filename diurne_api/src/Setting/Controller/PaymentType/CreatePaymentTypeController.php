<?php

namespace App\Setting\Controller\PaymentType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\PaymentType\CreatePaymentTypeCommand;
use App\Setting\DTO\CreatePaymentTypeRequestDto;
use App\Setting\Entity\PaymentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/payment-type', name: 'payment_type_create', methods: ['POST'])]
class CreatePaymentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'PaymentType Create',
        content: new Model(type: PaymentType::class)
    )]
    #[OA\RequestBody(
        description: 'PaymentType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'label', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreatePaymentTypeRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $createCommand = new CreatePaymentTypeCommand(
            $requestDTO->label
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'payment_type_create',
            $response->toArray()
        );
    }
}
