<?php

namespace App\Setting\Controller;

use App\Setting\Entity\MaterialPrice;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\MaterialPrice\DeleteMaterialPriceCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/deleteMaterialPrice/{id}', name: 'delete_material_price', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Material price deleted',
    content: new Model(type: MaterialPrice::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteMaterialPriceController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteMaterialPriceCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'material_price_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

