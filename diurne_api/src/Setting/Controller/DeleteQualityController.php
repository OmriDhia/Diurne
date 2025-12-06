<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Quality;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Quality\DeleteQualityCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/quality/{id}', name: 'delete_quality', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Quality deleted',
    content: new Model(type: Quality::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteQualityController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteQualityCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'quality_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
