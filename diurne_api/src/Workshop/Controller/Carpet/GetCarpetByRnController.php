<?php

namespace App\Workshop\Controller\Carpet;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetCarpetByRn\GetCarpetByRnQuery;
use App\Workshop\Entity\Carpet;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCarpetByRnController extends CommandQueryController
{
    #[Route('/api/carpet/rnNumber/{rnNumber}', name: 'carpet_get_by_rn', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a single carpet by rn number',
        content: new Model(type: Carpet::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Carpet not found'
    )]
    #[OA\Parameter(
        name: 'rnNumber',
        description: 'Carpet rn number',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(string $rnNumber): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetCarpetByRnQuery($rnNumber);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'carpet_retrieved',
            $response->toArray()
        );
    }
}
