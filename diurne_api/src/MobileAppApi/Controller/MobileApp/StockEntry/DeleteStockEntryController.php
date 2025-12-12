<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockEntry;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\StockEntry\DeleteStockEntry\DeleteStockEntryCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteStockEntryController extends CommandQueryController
{
    #[Route('/api/mobile/stock-entries/{id}', name: 'delete_stock_entry', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete StockEntry'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteStockEntryCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'delete_stock_entry',
            [],
            'StockEntry deleted successfully'
        );
    }
}
