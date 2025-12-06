<?php

namespace App\ProgressReport\Controller\ProvisionalCalendar;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProvisionalCalendar\CreateProvisionalCalendar\CreateProvisionalCalendarCommand;
use App\ProgressReport\DTO\ProvisionalCalendar\CreateProvisionalCalendarRequestDto;
use App\ProgressReport\Entity\ProvisionalCalendar;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Create a provisional calendar entry.
 */
class CreateProvisionalCalendarController extends CommandQueryController
{
    #[Route('/api/provisionalCalendar', name: 'provisional_calendar_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: ProvisionalCalendar::class))]
    #[OA\RequestBody(
        description: 'Provisional calendar data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'workshopOrderId', type: 'integer', example: 1),
                new OA\Property(property: 'deadlinPreparation', type: 'integer', example: 3),
                new OA\Property(property: 'deadlinWeave', type: 'integer', example: 5),
                new OA\Property(property: 'deadlinFinition', type: 'integer', example: 7),
                new OA\Property(property: 'eventPreparation', type: 'string', nullable: true),
                new OA\Property(property: 'stopPreparation', type: 'string', nullable: true),
                new OA\Property(property: 'eventWeave', type: 'string', nullable: true),
                new OA\Property(property: 'stopWeave', type: 'string', nullable: true),
                new OA\Property(property: 'eventFinition', type: 'string', nullable: true),
                new OA\Property(property: 'stopFinition', type: 'string', nullable: true)
            ]
        )
    )]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(#[MapRequestPayload] CreateProvisionalCalendarRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateProvisionalCalendarCommand(
            workshopOrderId: $dto->workshopOrderId,
            deadlinPreparation: $dto->deadlinPreparation,
            deadlinWeave: $dto->deadlinWeave,
            deadlinFinition: $dto->deadlinFinition,
            eventPreparation: $dto->eventPreparation,
            stopPreparation: $dto->stopPreparation,
            eventWeave: $dto->eventWeave,
            stopWeave: $dto->stopWeave,
            eventFinition: $dto->eventFinition,
            stopFinition: $dto->stopFinition,
        );
        $response = $this->handle($command);
        return SuccessResponse::create('provisional_calendar_created', $response->toArray(), 'Created', 201);
    }
}


