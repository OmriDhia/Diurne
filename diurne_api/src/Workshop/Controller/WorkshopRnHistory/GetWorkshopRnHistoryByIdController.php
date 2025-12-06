<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopRnHistory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopRnHistoryById\GetWorkshopRnHistoryByIdQuery;
use App\Workshop\Entity\WorkshopRnHistory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopRnHistoryByIdController extends CommandQueryController
{
    #[Route('/api/workshopRnHistories/{id}', name: 'workshop_rn_history_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns single workshop RN history',
        content: new Model(type: WorkshopRnHistory::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop RN history not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop RN history ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetWorkshopRnHistoryByIdQuery($id);
        $workshopRnHistory = $this->ask($query);
        if (empty($workshopRnHistory->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event category not found'],
                404
            );
        }
        return SuccessResponse::create(
            'workshop_rn_history_retrieved',
            $workshopRnHistory->toArray()
        );
    }
}