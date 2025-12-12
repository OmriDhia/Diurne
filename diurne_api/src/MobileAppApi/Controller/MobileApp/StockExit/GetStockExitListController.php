<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockExit;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\StockExit\GetStockExitList\GetStockExitListQuery;
use App\MobileAppApi\Entity\StockExit;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStockExitListController extends CommandQueryController
{
    #[Route('/api/mobile/stock-exits', name: 'get_stock_exit_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get list of StockExits',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: StockExit::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetStockExitListQuery();
        $response = $this->ask($query);

        $data = array_map(fn(StockExit $e) => $e->toArray(), $response);

        return SuccessResponse::create(
            'get_stock_exit_list',
            $data,
            'StockExit list retrieved successfully'
        );
    }
}
