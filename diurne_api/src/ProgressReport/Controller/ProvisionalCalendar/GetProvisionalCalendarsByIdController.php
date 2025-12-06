<?php

namespace App\ProgressReport\Controller\ProvisionalCalendar;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Query\ProvisionalCalendar\GetProvisionalCalendarById\GetProvisionalCalendarByIdQuery;
use App\ProgressReport\Entity\ProvisionalCalendar;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Get provisional calendar entry by id.
 */
class GetProvisionalCalendarsByIdController extends CommandQueryController
{
    #[Route('/api/provisionalCalendar/{id}', name: 'provisional_calendar_get_by_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Item', content: new Model(type: ProvisionalCalendar::class))]
    #[OA\Parameter(name: 'id', description: 'Provisional calendar id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetProvisionalCalendarByIdQuery($id));
        return SuccessResponse::create('provisional_calendar_item', $response->toArray());
    }
}


