<?php

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Model\UpdateModelCommand;
use App\Setting\DTO\UpdateModelRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/models/{id}', name: 'update_model', methods: ['PUT'])]
#[OA\Response(
    response: 200,
    description: 'Model updated',
    content: new Model(type: \App\Setting\Entity\Model::class)
)]
#[OA\RequestBody(
    description: 'Model data to update',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'string'),
            new OA\Property(property: 'number_max', type: 'integer'),
        ]
    )
)]
#[OA\Tag(name: 'Setting')]
class UpdateModelController extends CommandQueryController
{
    public function __invoke(int $id, #[MapRequestPayload] UpdateModelRequestDto $requestDTO): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $updateCommand = new UpdateModelCommand($id, $requestDTO->code, $requestDTO->number_max);

        try {
            $response = $this->handle($updateCommand);
            return SuccessResponse::create(
                'model_update',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
