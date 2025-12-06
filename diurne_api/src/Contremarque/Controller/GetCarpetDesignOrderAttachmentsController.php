<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetDesignOrderAttachments\GetCarpetDesignOrderAttachmentsQuery;
use App\Contremarque\Entity\Attachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetDesignOrderAttachmentsController extends CommandQueryController
{
    #[Route('/api/carpetDesignOrderAttachments/{carpetDesignOrderId}', name: 'get_carpet_design_order_attachments', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of Attachments by CarpetOrderDessign',
        content: new Model(type: Attachment::class)
    )]
    #[OA\Parameter(
        name: 'carpetOrderDesignId',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $carpetDesignOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetCarpetDesignOrderAttachmentsQuery($carpetDesignOrderId);
        $response = $this->ask($query);

        return SuccessResponse::create('get_carpet_design_order_attachments', $response->toArray());
    }
}
