<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformationMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateWorkshopInformationMaterial\UpdateWorkshopInformationMaterialCommand;
use App\Workshop\DTO\WorkshopInformationMaterial\UpdateWorkshopInformationMaterialDto;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateWorkshopInformationMaterialController extends CommandQueryController
{
    #[Route('/api/workshop-information-materials', name: 'workshop_information_material_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information material updated',
        content: new Model(type: WorkshopInformationMaterial::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop information material update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'materialId', type: 'integer'),
                new OA\Property(property: 'rate', type: 'number', format: 'float'),
                new OA\Property(property: 'price', type: 'number', format: 'float'),
                new OA\Property(property: 'workshopInformationId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(#[MapRequestPayload] UpdateWorkshopInformationMaterialDto $requestDto): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to update workshop information material'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $command = new UpdateWorkshopInformationMaterialCommand(
            id: $requestDto->id,
            materialId: $requestDto->materialId,
            rate: $requestDto->rate,
            price: $requestDto->price,
            workshopInformationId: $requestDto->workshopInformationId,
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_information_material_updated',
                $response->toArray(),
                'Workshop information material updated successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
