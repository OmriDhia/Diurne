<?php

namespace App\Workshop\Controller\HistoryEventCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateHistoryEventCategory\UpdateHistoryEventCategoryCommand;

use App\Workshop\DTO\HistoryEventCategory\UpdateHistoryEventCategoryDto;

use App\Workshop\Entity\HistoryEventCategory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateHistoryEventCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventCategory/{id}', name: 'history_event_category_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'History event category updated',
        content: new Model(type: HistoryEventCategory::class)
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'name', type: 'string', maxLength: 50)]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                $id,
        #[MapRequestPayload] UpdateHistoryEventCategoryDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new UpdateHistoryEventCategoryCommand($id, $dto->name);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_category_updated',
                $response->toArray(),
                'History event category updated successfully.'
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