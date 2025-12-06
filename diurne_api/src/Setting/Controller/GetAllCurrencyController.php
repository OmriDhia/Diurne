<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Currency\GetAllCurrencyQuery;
use App\Setting\Entity\Currency;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/currency', name: 'currency_get_all', methods: ['GET'])]
class GetAllCurrencyController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available currency',
        content: new Model(type: Currency::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all Currency',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllCurrencyQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'currency_get_all',
            $response->toArray(),
            'Currency fetched successfully'
        );
    }
}
