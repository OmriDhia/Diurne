<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Thread\DeleteThreadCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteThreadController extends CommandQueryController
{
    #[Route('/api/CarpetComposition/{carpetCompositionId}/Thread/{threadId}/delete', name: 'thread_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Layer deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $carpetCompositionId, int $threadId): JsonResponse
    {
        // Check if the user is authorized to delete the thread
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Create and dispatch the delete thread command
        $deleteCommand = new DeleteThreadCommand($carpetCompositionId, $threadId);
        $response = $this->handle($deleteCommand);

        // Return a success response
        return SuccessResponse::create(
            'thread_delete',
            $response->toArray(),
            'Thread deleted successfully'
        );
    }
}
