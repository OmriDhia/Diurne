<?php

namespace App\CheckingList\Controller\LayersValidation;

use App\CheckingList\Bus\Command\CreateLayersValidation\CreateLayersValidationCommand;
use App\CheckingList\DTO\LayersValidation\CreateLayersValidationRequestDto;
use App\CheckingList\Entity\LayersValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateLayersValidationController extends CommandQueryController
{
    #[Route('/api/layersValidations', name: 'layers_validation_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: LayersValidation::class))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapRequestPayload] CreateLayersValidationRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateLayersValidationCommand(
            checkingListId: $dto->checking_list_id,
            layerComment: $dto->layer_comment,
            layerValidation: $dto->layer_validation,
        );
        $response = $this->handle($command);

        return SuccessResponse::create('layers_validation_created', $response->toArray(), 'Layers validation created', Response::HTTP_CREATED);
    }
}
