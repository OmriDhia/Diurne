<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Carrier;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Carrier\DeleteCarrierCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/carrier/{id}', name: 'delete_carrier', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Carrier deleted',
    content: new Model(type: Carrier::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteCarrierController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteCarrierCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'carrier_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
