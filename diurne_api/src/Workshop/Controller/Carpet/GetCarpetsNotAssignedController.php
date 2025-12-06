<?php

namespace App\Workshop\Controller\Carpet;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetCarpetsNotAssigned\GetCarpetsNotAssignedQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCarpetsNotAssignedController extends CommandQueryController
{
    #[Route('/api/carpetsNotAssigned', name: 'carpets_not_assigned', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of carpets not assigned',

    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        $query = new GetCarpetsNotAssignedQuery();

        $response = $this->ask($query);

        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'No carpets found'],
                404
            );
        }

        return SuccessResponse::create(
            'carpets_not_assigned_list',
            $response->toArray()
        );
    }
}
