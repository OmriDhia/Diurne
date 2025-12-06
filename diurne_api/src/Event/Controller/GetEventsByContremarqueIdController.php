<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Query\GetEventsByContremarqueId\GetEventsByContremarqueIdQuery;
use App\Event\Entity\Event;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GetEventsByContremarqueIdController extends CommandQueryController
{
    #[Route('/api/contremarque/{contremarqueId}/events', name: 'get_events_by_contremarque_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get events by contremarque id',
        content: new Model(type: Event::class)
    )]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        $contremarqueId,
        Request $request
    ): JsonResponse {
        if (!$this->isGranted('read', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getEventsByContremarqueIdQuery = new GetEventsByContremarqueIdQuery($contremarqueId);
        $response = $this->ask($getEventsByContremarqueIdQuery);

        return SuccessResponse::create(
            'get_events_by_contremarque_id',
            $response
        );
    }
}
