<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetRegulations\GetRegulationsQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetRegulationsController extends CommandQueryController
{
    #[Route('/api/regulations', name: 'get_regulations', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'get regulations')]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $query = new GetRegulationsQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('get_regulations', $response->toArray());
    }
}
