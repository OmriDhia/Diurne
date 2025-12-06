<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ImageType\UpdateImageTypeCommand;
use App\Setting\DTO\UpdateImageTypeRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateImageTypeController extends CommandQueryController
{
    #[Route('/api/image-type/{id}', name: 'image_type_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'ImageType updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateImageTypeRequestDto::class)
        )
    )]
    #[OA\Response(response: 400, description: 'Bad Request')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'description', type: 'string', nullable: true),
                new OA\Property(property: 'category', type: 'string', nullable: true),

            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int                                            $id,
        #[MapRequestPayload] UpdateImageTypeRequestDto $updateDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCommand = new UpdateImageTypeCommand(
            $id,
            $updateDto->name,
            $updateDto->description,
            $updateDto->category
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'image_type_update',
            $response->toArray()
        );
    }
}
