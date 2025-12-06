<?php

declare(strict_types=1);

namespace App\Workshop\Controller\Carpet;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetCarpetById\GetCarpetByIdQuery;
use App\Workshop\Entity\Carpet;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCarpetByIdController extends CommandQueryController
{
    #[Route('/api/carpets/{id}', name: 'carpet_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a single carpet',
        content: new Model(type: Carpet::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Carpet not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Carpet ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetCarpetByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'carpet_retrieved',
            $response->toArray()
        );
    }
}
