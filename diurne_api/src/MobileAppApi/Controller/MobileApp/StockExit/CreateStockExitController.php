<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\StockExit;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\StockExit\CreateStockExit\CreateStockExitCommand;
use App\MobileAppApi\DTO\StockExit\CreateStockExitRequestDto;
use App\MobileAppApi\Entity\StockExit;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateStockExitController extends CommandQueryController
{
    #[Route('/api/mobile/stock-exits', name: 'create_stock_exit', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Stock exit creation',
        content: new Model(type: StockExit::class)
    )]
    #[OA\RequestBody(
        description: 'Stock exit data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnId', type: 'integer'),
                new OA\Property(property: 'location', type: 'string'),
                new OA\Property(property: 'userId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateStockExitRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateStockExitCommand(
            $requestDto->rnId,
            $requestDto->location,
            $requestDto->userId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_stock_exit',
            $response->toArray(),
            'StockExit created successfully'
        );
    }
}
