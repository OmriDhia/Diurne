<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Material\CreateMaterialCommand;
use App\Setting\DTO\CreateMaterialRequestDto;
use App\Setting\Entity\Material;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createMaterial', name: 'material_creation', methods: ['POST'])]
class CreateMaterialController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Material creation',
        content: new Model(type: Material::class)
    )]
    #[OA\RequestBody(
        description: 'Material data',
        content: new OA\JsonContent(
            required: ['reference'],
            properties: [
                new OA\Property(property: 'reference', type: 'string'),
                new OA\Property(
                    property: 'descriptions',
                    type: 'array',
                    items: new OA\Items(
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
        #[MapRequestPayload] CreateMaterialRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateMaterialCommand(
            $requestDTO->reference,
            $requestDTO->descriptions
        );

        $materialResponse = $this->handle($createCommand);

        return SuccessResponse::create(
            'material_creation',
            $materialResponse->toArray()
        );
    }
}
