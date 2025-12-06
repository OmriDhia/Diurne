<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformationMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateWorkshopInformationMaterial\CreateWorkshopInformationMaterialCommand;
use App\Workshop\DTO\WorkshopInformationMaterial\CreateWorkshopInformationMaterialDto;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateWorkshopInformationMaterialController extends CommandQueryController
{
    #[Route('/api/workshop-information-materials', name: 'workshop_information_material_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information material created',
        content: new Model(type: WorkshopInformationMaterial::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop information material data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'materialId', type: 'integer'),
                new OA\Property(property: 'rate', type: 'number', format: 'float'),
                new OA\Property(property: 'price', type: 'number', format: 'float'),
                new OA\Property(property: 'workshopInformationId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(#[MapRequestPayload] CreateWorkshopInformationMaterialDto $requestDto): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to create workshop information material'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $command = new CreateWorkshopInformationMaterialCommand(
            materialId: $requestDto->materialId,
            rate: $requestDto->rate,
            workshopInformationId: $requestDto->workshopInformationId,
            price: $requestDto->price ?? null,
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_information_material_created',
                $response->toArray(),
                'Workshop information material created successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
