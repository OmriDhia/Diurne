<?php

namespace App\Workshop\Controller\Carpet;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetCarpets\GetCarpetsQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCarpetsListController extends CommandQueryController
{
    #[Route('/api/carpets', name: 'carpets_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of carpets',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'rnNumber', type: 'string'),
                            // Add other properties for documentation
                        ],
                        type: 'object'
                    )
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer'),
                        new OA\Property(property: 'pages', type: 'integer')
                    ],
                    type: 'object'
                )
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        $query = new GetCarpetsQuery();

        $response = $this->ask($query);

        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'No carpets found'],
                404
            );
        }

        return SuccessResponse::create(
            'carpets_list',
            $response->toArray()
        );
    }
}