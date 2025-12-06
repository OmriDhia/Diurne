<?php

namespace App\Workshop\Controller\HistoryEventTypeCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteHistoryEventTypeCategory\DeleteHistoryEventTypeCategoryCommand;
use App\Workshop\Bus\Command\DeleteHistoryEventTypeCategory\DeleteHistoryEventTypeCategoryResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteHistoryEventTypeCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventTypeCategory/{id}', name: 'history_event_type_category_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'History event type category deleted',
        content: new Model(type: DeleteHistoryEventTypeCategoryResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'HistoryEventTypeCategory ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new DeleteHistoryEventTypeCategoryCommand($id);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_category_deleted',
                $response->toArray(),
                'History event type category deleted successfully.'
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