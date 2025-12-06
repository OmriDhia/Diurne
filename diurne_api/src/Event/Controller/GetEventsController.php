<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Query\GetEvents\GetEventsQuery;
use App\Event\DTO\GetEventsQueryDto;
use App\Event\Entity\Event;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetEventsController extends CommandQueryController
{
    #[Route('/api/events', name: 'get_events', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get events',
        content: new Model(type: Event::class)
    )]
    #[OA\RequestBody(
        description: 'Event data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemsPerPage', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        #[MapQueryString] GetEventsQueryDto $query,
    ): JsonResponse {
        if (!$this->isGranted('read', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getEvents = new GetEventsQuery(
            $query->page ?? null,
            $query->itemsPerPage ?? null,
            $query->filter->firstname ?? null,
            $query->filter->lastname ?? null,
            $query->filter->email ?? null,
            $query->filter->tvaCe ?? null,
            $query->filter->website ?? null,
            $query->filter->contact ?? null,
            $query->filter->prescripteur ?? null,
            $query->filter->socialReason ?? null,
            $query->filter->commercial ?? null,
            $query->filter->active ?? null,
            $query->filter->hasInvalidCommercial ?? null,
            $query->filter->hasOnlyOneContact ?? null,
            $query->filter->onlyLastEvent ?? null,
            $query->filter->hasNoProject ?? null,
            $query->filter->hasNextStep ?? null,
            $query->filter->eventDate_from ?? null,
            $query->filter->eventDate_to ?? null,
            $query->filter->next_reminder_deadline_from ?? null,
            $query->filter->next_reminder_deadline_to ?? null,
            $query->filter->subject ?? null,
            $query->filter->customerGroups ?? null,
            $query->filter->nomenclatureId ?? null,
            $query->filter->contremarqueId ?? null,
            $query->filter->customerId ?? null,
            $query->filter->quoteId ?? null,
            $query->orderBy ?? null,
            $query->orderWay ?? null,
        );

        $response = $this->ask($getEvents);

        return SuccessResponse::create(
            'get_events',
            $response
        );
    }
}
