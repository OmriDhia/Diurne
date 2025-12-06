<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Command\Event\CreateEventConfigurationCommand;
use App\Event\DTO\CreateEventConfigurationRequestDto;
use App\Event\Entity\EventNomenclature;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateEventConfigurationController extends CommandQueryController
{
    #[Route('/api/createEventConfiguration', name: 'event_configuration_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Event configuration creation',
        content: new Model(type: EventNomenclature::class)
    )]
    #[OA\RequestBody(
        description: 'Event configuration data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'subject', type: 'string'),
                new OA\Property(property: 'is_automatic', type: 'boolean'),
                new OA\Property(property: 'automatic_followup_delay', type: 'integer'),
            ]
        ))]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        #[MapRequestPayload] CreateEventConfigurationRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $createEventConfigurationCommand = new CreateEventConfigurationCommand($requestDTO->subject, (bool) $requestDTO->is_automatic);
        $createEventConfigurationCommand->setAutomaticFollowupDelay((int) $requestDTO->automatic_followup_delay);
        $eventConfigurationResponse = $this->handle($createEventConfigurationCommand);

        return SuccessResponse::create(
            'event_configuration_creation',
            $eventConfigurationResponse->toArray()
        );
    }
}
