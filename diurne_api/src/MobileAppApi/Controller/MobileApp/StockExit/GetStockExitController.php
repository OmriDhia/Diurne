<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockExit;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\StockExit\GetStockExit\GetStockExitQuery;
use App\MobileAppApi\Entity\StockExit;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStockExitController extends CommandQueryController
{
    #[Route('/api/mobile/stock-exits/{id}', name: 'get_stock_exit', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get StockExit',
        content: new Model(type: StockExit::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetStockExitQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_stock_exit',
            $response->toArray(),
            'StockExit retrieved successfully'
        );
    }
}
