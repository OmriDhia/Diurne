<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetAllPriceType\GetAllPriceTypesQuery;
use App\Setting\Bus\Query\GetAllPriceType\GetAllPriceTypesResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/price-types', name: 'get_all_price_types', methods: ['GET'])]
class GetAllPriceTypesController extends CommandQueryController
{
    #[OA\Get(
        description: 'Retrieve all PriceTypes',
        summary: 'Get all price types',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of price types',
                content: new Model(type: GetAllPriceTypesResponse::class)
            ),
        ]
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Dispatch the query to get all price types
        $query = new GetAllPriceTypesQuery();
        /** @var GetAllPriceTypesResponse $response */
        $response = $this->ask($query);

        // Return a successful JSON response with the data
        return SuccessResponse::create(
            'get_all_price_types',
            $response->toArray()
        );
    }
}
