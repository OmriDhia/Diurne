<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Color\UpdateColorCommand;
use App\Setting\DTO\UpdateColorRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateColorController extends CommandQueryController
{
    #[Route('/api/color/{id}', name: 'color_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Color updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateColorRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'reference', type: 'string'),
                new OA\Property(property: 'hexCode', type: 'string', nullable: true),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateColorRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateColorCommand(
            $id,
            $updateDto->reference,
            $updateDto->hexCode
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'color_update',
            $response->toArray()
        );
    }
}
