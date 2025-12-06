<?php

declare(strict_types=1);

namespace App\ProgressReport\Controller\ProvisionalCalendar;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarsByWorkshopOrderId\GetProvisionalCalendarsByWorkshopOrderIdQuery;
use App\ProgressReport\Entity\ProvisionalCalendar;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProvisionalCalendarsByWorkshopOrderIdController extends CommandQueryController
{
    #[Route('/api/provisionalCalendar/workshopOrder/{workshopOrderId}', name: 'provisional_calendar_get_by_workshop_order_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: ProvisionalCalendar::class)))]))]
    #[OA\Parameter(name: 'workshopOrderId', in: 'path', description: 'Workshop order id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(int $workshopOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetProvisionalCalendarsByWorkshopOrderIdQuery($workshopOrderId));

        return SuccessResponse::create('provisional_calendar_list', $response->toArray());
    }
}
