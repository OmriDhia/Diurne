<?php

declare(strict_types=1);

namespace App\Setting\Controller\TarifTexture;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TarifTexture\UpdateTarifTextureCommand;
use App\Setting\DTO\UpdateTarifTextureRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateTarifTextureController extends CommandQueryController
{
    #[Route('/api/tarifTexture/{id}', name: 'tariftexture_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'TarifTexture updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateTarifTextureRequestDto::class)
        )
    )]
    #[OA\Response(response: 400, description: 'Bad Request')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['year'],
            properties: [
                new OA\Property(property: 'year', type: 'string')
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int                                               $id,
        #[MapRequestPayload] UpdateTarifTextureRequestDto $updateDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCommand = new UpdateTarifTextureCommand($id, $updateDto->year);
        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'tariftexture_update',
            $response->toArray()
        );
    }
}

