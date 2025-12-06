<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\MaterialPrice\GetByIdMaterialPriceQuery;
use App\Setting\Entity\MaterialPrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdMaterialPriceController extends CommandQueryController
{
    #[Route('/api/materialPrice/{materialId}', name: 'get_by_id_materialPrice', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'MaterialPrice retrieval',
        content: new Model(type: MaterialPrice::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $materialId
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdMaterialPriceQuery($materialId);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            $response->toArray(),
            'get_by_id_materialPrice'
        );
    }
}
