<?php

namespace App\Workshop\Controller\WorkshopRnHistory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteWorkshopRnHistory\DeleteWorkshopRnHistoryCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteWorkshopRnHistoryController extends CommandQueryController
{
    #[Route('/api/workshopRnHistories/{id}', name: 'workshop_rn_history_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop RN history deleted',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'status', type: 'string'),
            new OA\Property(property: 'message', type: 'string'),
            new OA\Property(property: 'data', type: 'object')
        ])
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop RN History ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to delete workshop RN history'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            $response = $this->handle(new DeleteWorkshopRnHistoryCommand($id));

            return SuccessResponse::create(
                'workshop_rn_history_deletion',
                $response->toArray(),
                'Workshop RN history deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}