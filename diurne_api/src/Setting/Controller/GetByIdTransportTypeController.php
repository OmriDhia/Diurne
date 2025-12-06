<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TransportType\GetByIdTransportTypeQuery;
use App\Setting\Entity\TransportType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdTransportTypeController extends CommandQueryController
{
    #[Route('/api/transportType/{id}', name: 'get_by_id_transportType', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'TransportType retrieval',
        content: new Model(type: TransportType::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdTransportTypeQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            'get_by_id_transportType',
            $response->toArray()
        );
    }
}
