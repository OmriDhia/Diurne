<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopRnHistory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopRnHistoryByWorkshopOrderId\GetWorkshopRnHistoryByWorkshopOrderIdQuery;
use App\Workshop\Entity\WorkshopRnHistory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopRnHistoryByWorkshopOrderIdController extends CommandQueryController
{
    #[Route('/api/workshopRnHistories/workshopOrder/{workshopOrderId}', name: 'workshop_rn_history_get_by_workshop_order_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: WorkshopRnHistory::class)))]))]
    #[OA\Parameter(name: 'workshopOrderId', in: 'path', description: 'Workshop order id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetWorkshopRnHistoryByWorkshopOrderIdQuery($workshopOrderId));

        return SuccessResponse::create('workshop_rn_history_list', $response->toArray());
    }
}
