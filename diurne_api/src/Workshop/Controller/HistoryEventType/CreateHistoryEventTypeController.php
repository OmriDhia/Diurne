<?php

namespace App\Workshop\Controller\HistoryEventType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateHistoryEventType\CreateHistoryEventTypeCommand;
use App\Workshop\DTO\HistoryEventTypes\CreateHistoryEventTypeDto;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateHistoryEventTypeController extends CommandQueryController
{
    #[Route('/api/historyEventTypes', name: 'history_event_type_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'History event type created',
        content: new Model(type: HistoryEventType::class)
    )]
    #[OA\RequestBody(
        description: 'Create history event type',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', maxLength: 50)
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateHistoryEventTypeDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new CreateHistoryEventTypeCommand($dto->name);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_created',
                $response->toArray(),
                'History event type created successfully.',
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