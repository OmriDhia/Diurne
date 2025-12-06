<?php

namespace App\Setting\Controller\PaymentType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\PaymentType\DeletePaymentTypeCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/payment-type/{id}', name: 'payment_type_delete', methods: ['DELETE'])]
class DeletePaymentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Payment Type deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeletePaymentTypeCommand($id);
        $this->handle($deleteCommand);

        return SuccessResponse::create(
            'attachment_type_delete',
            null
        );
    }

}