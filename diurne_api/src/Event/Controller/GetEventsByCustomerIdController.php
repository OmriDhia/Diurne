<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Query\GetEventsByCustomerId\GetEventsByCustomerIdQuery;
use App\Event\Entity\Event;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GetEventsByCustomerIdController extends CommandQueryController
{
    #[Route('/api/customer/{customerId}/events', name: 'get_events_by_customer_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get events by customer id',
        content: new Model(type: Event::class)
    )]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
        $customerId,
        Request $request
    ): JsonResponse {
        if (!$this->isGranted('read', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $contremarqueId = $request->query->get('contremarqueId');
        $getEventsByCustomerIdQuery = new GetEventsByCustomerIdQuery($customerId, $contremarqueId);
        $response = $this->ask($getEventsByCustomerIdQuery);

        return SuccessResponse::create(
            'get_events_by_customer_id',
            $response
        );
    }
}
