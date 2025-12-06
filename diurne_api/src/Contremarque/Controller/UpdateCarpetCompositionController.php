<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\UpdateCarpetCompositionCommand;
use App\Contremarque\DTO\UpdateCarpetCompositionRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCarpetCompositionController extends CommandQueryController
{
    #[Route('/api/CarpetComposition/{id}/update', name: 'carpetComposition_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'CarpetComposition updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateCarpetCompositionRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['threadCount', 'layerCount'],
            properties: [
                new OA\Property(property: 'trame', type: 'string'),
                new OA\Property(property: 'threadCount', type: 'int'),
                new OA\Property(property: 'layerCount', type: 'int'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateCarpetCompositionRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateCarpetCompositionCommand(
            $id,
            $updateDto->trame ?? null,
            $updateDto->threadCount,
            $updateDto->layerCount,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'carpetComposition_update',
            $response->toArray(),
            'CarpetComposition updated successfully.',
        );
    }
}
