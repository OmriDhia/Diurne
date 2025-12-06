<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GenerateDiNumber\GenerateDemandNumberQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GenerateDemandNumberController extends CommandQueryController
{
    #[Route('/api/generateDemandNumber', name: 'generate_demand_number', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Generate Demand Number'
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GenerateDemandNumberQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'generate_demand_number',
            $response->toArray(),
            'Generate Demand Number'
        );
    }
}
