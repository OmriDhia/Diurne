<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockExit;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\StockExit\DeleteStockExit\DeleteStockExitCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteStockExitController extends CommandQueryController
{
    #[Route('/api/mobile/stock-exits/{id}', name: 'delete_stock_exit', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete StockExit'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteStockExitCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'delete_stock_exit',
            [],
            'StockExit deleted successfully'
        );
    }
}
