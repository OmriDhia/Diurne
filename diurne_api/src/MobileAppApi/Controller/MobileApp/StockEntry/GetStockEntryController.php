<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockEntry;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\StockEntry\GetStockEntry\GetStockEntryQuery;
use App\MobileAppApi\Entity\StockEntry;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetStockEntryController extends CommandQueryController
{
    #[Route('/api/mobile/stock-entries/{id}', name: 'get_stock_entry', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get StockEntry',
        content: new Model(type: StockEntry::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetStockEntryQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_stock_entry',
            $response->toArray(),
            'StockEntry retrieved successfully'
        );
    }
}
