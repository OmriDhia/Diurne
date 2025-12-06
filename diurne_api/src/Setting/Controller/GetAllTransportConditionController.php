<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TransportCondition\GetAllTransportConditionQuery;
use App\Setting\Entity\TransportCondition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/transportCondition', name: 'get_all_transportConditions', methods: ['GET'])]
class GetAllTransportConditionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available transportCondition',
        content: new Model(type: TransportCondition::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all TransportCondition',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllTransportConditionQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_transportConditions',
            $response->toArray(),
            'TransportConditions fetched successfully'
        );
    }
}
