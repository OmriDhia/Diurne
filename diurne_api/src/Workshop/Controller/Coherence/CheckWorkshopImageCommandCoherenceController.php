<?php

declare(strict_types=1);

namespace App\Workshop\Controller\Coherence;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\CheckWorkshopImageCommandCoherence\CheckWorkshopImageCommandCoherenceQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CheckWorkshopImageCommandCoherenceController extends CommandQueryController
{
    #[Route('/api/workshopImageCommandCoherence/{workshopOrderId}/{imageCommandId}', name: 'check_workshop_image_command_coherence', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Coherence check result')]
    #[OA\Response(response: 404, description: 'Resource not found')]
    #[OA\Response(response: 401, description: 'Unauthorized')]
    #[OA\Parameter(
        name: 'workshopOrderId',
        description: 'Workshop order ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'imageCommandId',
        description: 'Image command ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopOrderId, int $imageCommandId): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new CheckWorkshopImageCommandCoherenceQuery($workshopOrderId, $imageCommandId);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'workshop_image_command_coherence',
            $response->toArray(),
            $response->isCoherent() ? 'Coherent' : 'Differences found'
        );
    }
}
