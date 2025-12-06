<?php

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\AttachmentUpload\UploadFileCommand;
use App\Workshop\Bus\Command\CreateWorkshopImage\CreateWorkshopImageCommand;
use App\Workshop\DTO\WorkshopImage\CreateWorkshopImageRequestDto;
use App\Workshop\Entity\WorkshopImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateWorkshopImageController extends CommandQueryController
{

    #[Route('/api/workshopImages', name: 'workshop_image_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop image created',
        content: new Model(type: WorkshopImage::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop image data with file upload',
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "fileName", type: "string", maxLength: 50),
                    new OA\Property(property: "idImageType", type: "integer"),
                    new OA\Property(property: "format", type: "string", maxLength: 50),
                    new OA\Property(property: "locationId", type: "integer"),
                    new OA\Property(property: "workshopOrderId", type: "integer"),
                    new OA\Property(property: "file", type: "string", format: "binary"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time", nullable: true),
                    new OA\Property(property: "updatedAt", type: "string", format: "date-time", nullable: true)
                ]
            )
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        Request                                            $request,
        #[MapRequestPayload] CreateWorkshopImageRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to create workshop image'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return new JsonResponse(
                ['code' => 400, 'message' => 'No file uploaded'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            // First handle the file upload
            $uploadCommand = new UploadFileCommand($uploadedFile);
            $uploadCommand->setAttachmentTypeId($requestDTO->idImageType); // Assuming idImageType is the attachment type ID
            $attachment = $this->handle($uploadCommand);

            // Then create the workshop image with the attachment ID
            $command = new CreateWorkshopImageCommand(
                fileName: $requestDTO->fileName,
                idImageType: $requestDTO->idImageType,
                format: $requestDTO->format,
                locationId: $requestDTO->locationId,
                workshopOrderId: $requestDTO->workshopOrderId,
                attachmentId: $attachment->getId(),
                createdAt: $requestDTO->createdAt,
                updatedAt: $requestDTO->createdAt
            );

            $workshopImage = $this->handle($command);

            if (!$workshopImage) {
                throw new \RuntimeException('Failed to create workshop image');
            }

            return SuccessResponse::create(
                'workshop_image_creation',
                $workshopImage->toArray(),
                'Workshop image created successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}