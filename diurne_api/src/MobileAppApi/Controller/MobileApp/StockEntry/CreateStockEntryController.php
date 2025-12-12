<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockEntry;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\StockEntry\CreateStockEntry\CreateStockEntryCommand;
use App\MobileAppApi\DTO\StockEntry\CreateStockEntryRequestDto;
use App\MobileAppApi\Entity\StockEntry;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateStockEntryController extends CommandQueryController
{
    #[Route('/api/mobile/stock-entries', name: 'create_stock_entry', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'StockEntry creation',
        content: new Model(type: StockEntry::class)
    )]
    #[OA\RequestBody(
        description: 'StockEntry data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnId', type: 'integer'),
                new OA\Property(property: 'location', type: 'string'),
                new OA\Property(property: 'userId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateStockEntryRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateStockEntryCommand(
            $requestDto->rnId,
            $requestDto->location,
            $requestDto->userId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_stock_entry',
            $response->toArray(),
            'StockEntry created successfully'
        );
    }
}
