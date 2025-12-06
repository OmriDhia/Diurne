<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ManufacturerPriceGrid\DeleteManufacturerPriceGridCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Annotation\Model;

#[Route('/api/manufacturer-price-grids/{id}', name: 'manufacturer_price_grid_delete', methods: ['DELETE'])]
class DeleteManufacturerPriceGridController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price grid deleted successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Price grid ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new DeleteManufacturerPriceGridCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'manufacturer_price_grid_delete',
            [],
            'Manufacturer price grid deleted successfully'
        );
    }
}
