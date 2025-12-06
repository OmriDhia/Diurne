<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetAttachmentsByProjectDi\GetAttachmentsByProjectDiQuery;
use App\Contremarque\Entity\Attachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetAttachmentsByProjectDiController extends CommandQueryController
{
    #[Route('/api/projectDi/{id}/attachments', name: 'get_attachments_by_project_di', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of Attachments by ProjectDi',
        content: new Model(type: Attachment::class)
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetAttachmentsByProjectDiQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_attachments_by_project_di',
            $response->toArray()
        );
    }
}
