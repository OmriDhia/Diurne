<?php

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Model\DeleteModelCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/models/{id}', name: 'delete_model', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Model deleted',
    content: new Model(type: \App\Setting\Entity\Model::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteModelController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteModelCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'model_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
