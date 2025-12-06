<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TransportCondition\GetByIdTransportConditionQuery;
use App\Setting\Entity\TransportCondition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdTransportConditionController extends CommandQueryController
{
    #[Route('/api/transportCondition/{id}', name: 'get_by_id_transportCondition', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'TransportCondition retrieval',
        content: new Model(type: TransportCondition::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdTransportConditionQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            'get_by_id_transportCondition',
            $response->toArray(),
            'TransportCondition retrieved successfully'

        );
    }
}
