<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetTypes\GetCarpetTypesQuery;
use App\Contremarque\Bus\Query\GetCarpetTypes\GetCarpetTypesResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetTypesController extends CommandQueryController
{
    #[Route('/api/carpet-types', name: 'get_carpet_types', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get carpet types',
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

        $getCarpetTypesQuery = new GetCarpetTypesQuery();
        /** @var GetCarpetTypesResponse $response */
        $response = $this->ask($getCarpetTypesQuery);

        return SuccessResponse::create(
            'get_carpet_types',
            $response->toArray()
        );
    }
}
