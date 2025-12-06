<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Command\Event\CreateEventCommand;
use App\Event\Bus\Command\Event\CreateEventPeoplePresentCommand;
use App\Event\DTO\CreateEventRequestDto;
use App\Event\DTO\PeoplePresentDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateEventController extends CommandQueryController
{
    #[Route('/api/createEvent', name: 'event_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Event creation',
        content: new OA\JsonContent(
            ref: new Model(type: CreateEventRequestDto::class)
        )
    )]
    #[OA\RequestBody(
        description: 'Event data',
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
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        #[MapRequestPayload] CreateEventRequestDto $requestDTO
    ): JsonResponse {
        // Check permissions
        if (!$this->isGranted('create', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Create event command
        $createEventCommand = new CreateEventCommand();
        $createEventCommand->setPeoplePresent($requestDTO->people_present);
        $createEventCommand->setNextReminderDeadline($requestDTO->next_reminder_deadline ?? null);
        $createEventCommand->setEventDate($requestDTO->event_date);
        $createEventCommand->setCommentaire($requestDTO->commentaire);
        $createEventCommand->setDisabledReminder($requestDTO->reminder_disabled);
        $createEventCommand->setCustomerId($requestDTO->customerId);
        $createEventCommand->setQuoteId($requestDTO->quoteId);
        $createEventCommand->setContremarqueId($requestDTO->contremarqueId);
        $createEventCommand->setNomenclatureId($requestDTO->nomenclatureId);

        // Handle event creation command
        $eventResponse = $this->handle($createEventCommand);
        $eventData = $eventResponse->toArray();

        // Handle people present if available
        if (!empty($eventData) && !empty($requestDTO->people_present)) {
            $peoples = $requestDTO->people_present;
            $createPeoplePresentCommand = new CreateEventPeoplePresentCommand(
                $peoples->contacts,  // Assuming $peoples is a PeoplePresentDto with a contacts property
                $peoples->users,     // Assuming $peoples is a PeoplePresentDto with a users property
                $eventData['event_id']
            );
            $this->handle($createPeoplePresentCommand);
        }

        // Return success response
        return SuccessResponse::create(
            'event_creation',
            $eventResponse->toArray()
        );
    }
}
