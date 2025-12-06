<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetContremarqueByPrescriberId\GetContremarqueByPrescriberIdQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetContremarqueByPrescriberIdController extends CommandQueryController
{
    #[Route('/api/contremarques/prescriber/{id}', name: 'contremarques_get_by_prescriber_id', methods: ['GET'])]
    #[OA\Tag(name: 'Contremarque')]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Prescriber ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns Contremarques by Prescriber ID',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Contremarque'))
    )]
    public function __invoke(int $id): JsonResponse
    {
        // Create the query to get contremarques by prescriber ID
        $query = new GetContremarqueByPrescriberIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'contremarques_get_by_prescriber_id',
            $response->toArray()
        );
    }
}
