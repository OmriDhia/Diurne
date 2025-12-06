<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Material\UpdateMaterialCommand;
use App\Setting\DTO\UpdateMaterialRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/updateMaterial/{id}', name: 'update_material', methods: ['PUT'])]
class UpdateMaterialController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Material updated',
        content: new Model(type: SuccessResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Material not found'
    )]
    #[OA\RequestBody(
        description: 'Material data',
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(property: 'reference', type: 'string', nullable: true),
                new OA\Property(
                    property: 'descriptions',
                    type: 'array',
                    nullable: true,
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'language_id', type: 'integer'),
                            new OA\Property(property: 'label', type: 'string'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int                                           $id,
        #[MapRequestPayload] UpdateMaterialRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCommand = new UpdateMaterialCommand(
            $id,
            $requestDTO->reference ?? null,
            $requestDTO->descriptions ?? []
        );

        $materialResponse = $this->handle($updateCommand);

        return SuccessResponse::create(
            'update_material',
            $materialResponse->toArray()
        );
    }
}
