<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockEntry;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\StockEntry\GetStockEntryList\GetStockEntryListQuery;
use App\MobileAppApi\Entity\StockEntry;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStockEntryListController extends CommandQueryController
{
    #[Route('/api/mobile/stock-entries', name: 'get_stock_entry_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get list of Entries',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: StockEntry::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetStockEntryListQuery();
        $response = $this->ask($query);

        $data = array_map(fn(StockEntry $e) => $e->toArray(), $response);

        return SuccessResponse::create(
            'get_stock_entry_list',
            $data,
            'StockEntry list retrieved successfully'
        );
    }
}
