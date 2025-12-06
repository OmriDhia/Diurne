<?php

namespace App\Contremarque\Controller\TechnicalImage;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\TechnicalImage\DeleteTechnicalImageCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/technical-image/{id}', name: 'delete_technical_image', methods: ['DELETE'])]
class DeleteTechnicalImageController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Delete Technical image',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new DeleteTechnicalImageCommand($id);
        $this->handle($query);
        return SuccessResponse::create(
            'delete_technical_image',
            [],
            'Technical Image deleted successfully'

        );
    }
}
