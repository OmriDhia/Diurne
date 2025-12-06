<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\AttachmentType\GetAllAttachmentTypesQuery;
use App\Setting\Entity\AttachmentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/attachment-types', name: 'get_all_attachment_types', methods: ['GET'])]
class GetAllAttachmentTypesController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available attachment types',
        content: new Model(type: AttachmentType::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        // Authorization check
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Fetching all attachment types using CQRS query
        $query = new GetAllAttachmentTypesQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_attachment_types',
            $response->toArray(),

        );
    }
}
