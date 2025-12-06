<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Carrier\GetByIdCarrierQuery;
use App\Setting\Entity\Carrier;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdCarrierController extends CommandQueryController
{
    #[Route('/api/carrier/{id}', name: 'get_by_id_carrier', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Carrier retrieval',
        content: new Model(type: Carrier::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdCarrierQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            $response->toArray(),
            'get_by_id_carrier'
        );
    }
}
