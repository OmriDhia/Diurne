<?php

// src/Contremarque/Controller/AttachmentUploadController.php
declare(strict_types=1);

namespace App\Contremarque\Controller;

use InvalidArgumentException;
use Throwable;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\AttachmentUpload\UploadFileCommand;
use App\Contremarque\Bus\Command\AttachmentUpload\UploadFileResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AttachmentUploadController extends CommandQueryController
{
    #[Route('/api/upload/attachment', name: 'api_attachment_upload', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'File upload response',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'filename', type: 'string'),
                new OA\Property(property: 'uploadedAt', type: 'string', format: 'date-time'),
            ]
        )
    )]
    #[OA\RequestBody(
        description: 'Upload file or provide path from distant server',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'file', type: 'string', format: 'binary', nullable: true),
                new OA\Property(property: 'distantFilePath', type: 'string', nullable: true),
                new OA\Property(property: 'attachmentTypeId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['error' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Check if file is uploaded or distant file path is provided
        $file = $request->files->get('file');
        $distantFilePath = $request->get('distantFilePath');
        $attachmentTypeId = $request->get('attachmentTypeId');

        if (!$file && !$distantFilePath) {
            return new JsonResponse(['error' => 'No file or distant file path provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $command = new UploadFileCommand($file, $distantFilePath);
            $command->setAttachmentTypeId($attachmentTypeId);
            $attachment = $this->handle($command);
            $response = new UploadFileResponse($attachment);

            return SuccessResponse::create(
                'attachment_upload',
                $response->toArray()
            );
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {

            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
