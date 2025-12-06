<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Command\Event\CreateEventPeoplePresentCommand;
use App\Event\Bus\Command\Event\UpdateEventCommand;
use App\Event\DTO\UpdateEventRequestDto;
use App\Event\Repository\PeoplePresentRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateEventController extends CommandQueryController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly PeoplePresentRepository $peoplePresentRepository
    ) {
        parent::__construct($queryBus, $commandBus);
    }

    #[Route('/api/updateEvent/{eventId}', name: 'event_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Event updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateEventRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'nomenclatureId', type: 'integer'),
                new OA\Property(property: 'customerId', type: 'integer'),
                new OA\Property(property: 'contremarqueId', type: 'integer'),
                new OA\Property(property: 'quoteId', type: 'integer'),
                new OA\Property(property: 'reminder_disabled', type: 'boolean'),
                new OA\Property(property: 'commentaire', type: 'string'),
                new OA\Property(property: 'event_date', type: 'string'),
                new OA\Property(property: 'next_reminder_deadline', type: 'string'),
                new OA\Property(
                    property: 'people_present',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'contacts', type: 'array', items: new OA\Items(type: 'string')),
                            new OA\Property(property: 'users', type: 'array', items: new OA\Items(type: 'string')),
                        ]
                    )
                ),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        int $eventId,
        #[MapRequestPayload] UpdateEventRequestDto $updateEventRequestDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Handle the update event command
        $command = new UpdateEventCommand();
        $command->setEventId($eventId)
            ->setNomenclatureId($updateEventRequestDto->nomenclatureId)
            ->setCustomerId($updateEventRequestDto->customerId)
            ->setContremarqueId($updateEventRequestDto->contremarqueId)
            ->setQuoteId($updateEventRequestDto->quoteId)
            ->setCommentaire($updateEventRequestDto->commentaire)
            ->setPeoplePresent($updateEventRequestDto->people_present->toArray()) // Call toArray to get array representation
            ->setEventDate($updateEventRequestDto->event_date)
            ->setNextReminderDeadline($updateEventRequestDto->next_reminder_deadline)
            ->setDisabledReminder($updateEventRequestDto->reminder_disabled);

        $eventResponse = $this->handle($command);

        // Convert the event response to an array
        $eventData = $eventResponse->toArray();

        // If people_present is provided, update the associated entries
        if (!empty($eventData)) {
            if (!empty($updateEventRequestDto->people_present)) {
                // Delete old PeoplePresent records for the event
                $this->peoplePresentRepository->deleteByEventId($eventId);

                // Add new PeoplePresent records
                $peoples = $updateEventRequestDto->people_present->toArray(); // Ensure we use toArray here
                $createPeoplePresentCommand = new CreateEventPeoplePresentCommand(
                    $peoples['contacts'] ?? [],  // Access the contacts directly
                    $peoples['users'] ?? [],      // Access the users directly
                    $eventData['event_id']
                );
                $this->handle($createPeoplePresentCommand);
            }
        }

        return SuccessResponse::create(
            'event_update',
            $eventResponse->toArray()
        );
    }
}
