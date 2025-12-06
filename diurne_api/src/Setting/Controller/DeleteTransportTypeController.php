<?php

namespace App\Setting\Controller;

use App\Setting\Entity\TransportType;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TransportType\DeleteTransportTypeCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transportType/{id}', name: 'delete_transportType', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'TransportType deleted',
    content: new Model(type: TransportType::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteTransportTypeController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteTransportTypeCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'transportType_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
