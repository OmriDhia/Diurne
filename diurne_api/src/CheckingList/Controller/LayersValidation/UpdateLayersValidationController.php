<?php

namespace App\CheckingList\Controller\LayersValidation;

use App\CheckingList\Bus\Command\UpdateLayersValidation\UpdateLayersValidationCommand;
use App\CheckingList\DTO\LayersValidation\UpdateLayersValidationRequestDto;
use App\CheckingList\Entity\LayersValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateLayersValidationController extends CommandQueryController
{
    #[Route('/api/layersValidations/{id}', name: 'layers_validation_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: LayersValidation::class))]
    #[OA\Parameter(name: 'id', description: 'Layers validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateLayersValidationRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateLayersValidationCommand(
            id: $id,
            layerComment: $dto->layer_comment,
            layerValidation: $dto->layer_validation,
        );
        $response = $this->handle($command);

        return SuccessResponse::create('layers_validation_updated', $response->toArray(), 'Layers validation updated');
    }
}
