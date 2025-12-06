<?php

namespace App\Workshop\Controller\HistoryEventCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteHistoryEventCategory\DeleteHistoryEventCategoryCommand;
use App\Workshop\Bus\Command\DeleteHistoryEventCategory\DeleteHistoryEventCategoryResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteHistoryEventCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventCategory/{id}', name: 'history_event_category_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'History event category deleted',
        content: new Model(type: DeleteHistoryEventCategoryResponse::class)
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        try {
            $command = new DeleteHistoryEventCategoryCommand($id);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_category_deleted',
                $response->toArray(),
                'History event category deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}