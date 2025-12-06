<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetTarifExpeditions\GetTarifExpeditionsQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetTarifExpeditionsController extends CommandQueryController
{
    #[Route('/api/tarifExpeditions', name: 'get_tarif_expeditions', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'get tarif expeditions')]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $query = new GetTarifExpeditionsQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('get_tarif_expeditions', $response->toArray());
    }
}
