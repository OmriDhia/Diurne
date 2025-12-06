<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetMeasurements\GetMeasurementsQuery;
use App\Contremarque\Bus\Query\GetMeasurements\GetMeasurementsResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetMeasurementsController extends CommandQueryController
{
    #[Route('/api/measurements', name: 'get_measurements', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get measurements',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $getMeasurementsQuery = new GetMeasurementsQuery();
        /** @var GetMeasurementsResponse $response */
        $response = $this->ask($getMeasurementsQuery);

        return SuccessResponse::create(
            'get_measurements',
            $response->toArray()
        );
    }
}
