<?php

namespace App\Setting\Controller;

use App\Setting\Entity\CollectionGroupPrice;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroupPrice\DeleteCollectionGroupPriceCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/collection-group-price/{id}', name: 'delete_collectiongroupprice', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'CollectionGroupPrice deleted',
    content: new Model(type: CollectionGroupPrice::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteCollectionGroupPriceController  extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteCollectionGroupPriceCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'collectiongroupprice_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
