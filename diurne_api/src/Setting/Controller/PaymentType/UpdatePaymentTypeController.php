<?php

namespace App\Setting\Controller\PaymentType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\PaymentType\UpdatePaymentTypeCommand;
use App\Setting\DTO\UpdatePaymentTypeRequestDto;
use App\Setting\Entity\PaymentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/payment-type/{id}', name: 'payment_type_update', methods: ['PUT'])]
class UpdatePaymentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'PaymentType update',
        content: new Model(type: PaymentType::class)
    )]
    #[OA\RequestBody(
        description: 'PaymentType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'label', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int                                              $id,
        #[MapRequestPayload] UpdatePaymentTypeRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $updateCommand = new UpdatePaymentTypeCommand(
            $id,
            $requestDTO->label
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'payment_type_update',
            $response->toArray()
        );
    }
}
