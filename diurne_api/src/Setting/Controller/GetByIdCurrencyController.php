<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Currency\GetByIdCurrencyQuery;
use App\Setting\Entity\Currency;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdCurrencyController extends CommandQueryController
{
    #[Route('/api/currency/{id}', name: 'get_by_id_currency', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Currency retrieval',
        content: new Model(type: Currency::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdCurrencyQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            'get_by_id_currency',
            $response->toArray(),
            'Currency retrieved successfully'

        );
    }
}
