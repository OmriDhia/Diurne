<?php

namespace App\Workshop\Controller\HistoryEventCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateHistoryEventCategory\CreateHistoryEventCategoryCommand;
use App\Workshop\Bus\Command\CreateHistoryEventType\CreateHistoryEventTypeCommand;
use App\Workshop\DTO\HistoryEventCategory\CreateHistoryEventCategoryDto;
use App\Workshop\DTO\HistoryEventTypes\CreateHistoryEventTypeDto;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateHistoryEventCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventCategory', name: 'history_event_Category_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'History event Category created',
        content: new Model(type: HistoryEventType::class)
    )]
    #[OA\RequestBody(
        description: 'Create history event Category',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', maxLength: 50)
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateHistoryEventCategoryDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        try {
            $command = new CreateHistoryEventCategoryCommand($dto->name);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_Category_created',
                $response->toArray(),
                'History event Category created successfully.',
                JsonResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}