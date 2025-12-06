<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Query\GetAttachmentById\GetAttachmentByIdQuery;
use App\Contremarque\Bus\Query\GetAttachmentById\GetAttachmentByIdResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAttachmentByIdController extends CommandQueryController
{
    #[Route('/api/attachment/{id}', name: 'get_attachment_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get attachment by ID',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'filename', type: 'string'),
                new OA\Property(property: 'file', type: 'string', format: 'binary'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id, Request $request): Response
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $getAttachmentByIdQuery = new GetAttachmentByIdQuery($id);
        /** @var GetAttachmentByIdResponse $response */
        $response = $this->ask($getAttachmentByIdQuery);

        $attachment = $response->getAttachment();

        if (!$attachment) {
            return new JsonResponse(['error' => 'Attachment not found'], 404);
        }

        $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/attachments/'.$attachment->getFile();

        if (!file_exists($filePath)) {
            return new JsonResponse(['error' => 'File not found'], 404);
        }

        return new Response(
            file_get_contents($filePath),
            Response::HTTP_OK,
            [
                'Content-Type' => mime_content_type($filePath),
                'Content-Disposition' => 'inline; filename="'.$attachment->getFile().'"',
            ]
        );
    }
}
