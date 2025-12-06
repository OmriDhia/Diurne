<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateWorkshopImage\UpdateWorkshopImageCommand;
use App\Workshop\DTO\WorkshopImage\UpdateWorkshopImageRequestDto;
use App\Workshop\Entity\WorkshopImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateWorkshopImageController extends CommandQueryController
{
    #[Route('/api/workshopImages/{id}', name: 'workshop_image_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop image updated',
        content: new Model(type: WorkshopImage::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop image data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "fileName", type: "string", maxLength: 50),
                new OA\Property(property: "idImageType", type: "integer"),
                new OA\Property(property: "format", type: "string", maxLength: 50),
                new OA\Property(property: "locationId", type: "integer"),
                new OA\Property(property: "workshopOrderId", type: "integer"),
                new OA\Property(property: "attachmentId", type: "integer"),
                new OA\Property(property: "createdAt", type: "string", format: "date-time", nullable: true),
                new OA\Property(property: "updatedAt", type: "string", format: "date-time", nullable: true)
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop Image ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                $id,
        #[MapRequestPayload] UpdateWorkshopImageRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to update workshop image'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $command = new UpdateWorkshopImageCommand(
            id: $id,
            fileName: $requestDTO->fileName,
            idImageType: $requestDTO->idImageType,
            format: $requestDTO->format,
            locationId: $requestDTO->locationId,
            workshopOrderId: $requestDTO->workshopOrderId,
            attachmentId: $requestDTO->attachmentId,
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_image_update',
                $response->toArray(),
                'Workshop image updated successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }
}