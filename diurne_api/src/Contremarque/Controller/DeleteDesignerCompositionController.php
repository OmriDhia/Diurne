<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteDesignerComposition\DeleteDesignerCompositionCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteDesignerCompositionController extends CommandQueryController
{
    #[Route('/api/deleteDesignerComposition/{id}', name: 'designer_composition_deletion', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Designer Composition deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteDesignerCompositionCommand = new DeleteDesignerCompositionCommand($id);
        $this->handle($deleteDesignerCompositionCommand);

        return SuccessResponse::create(
            'designer_composition_deletion',
            [],
            'Designer Composition deleted successfully'
        );
    }
}
