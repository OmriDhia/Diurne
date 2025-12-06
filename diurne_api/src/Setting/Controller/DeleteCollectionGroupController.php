<?php

namespace App\Setting\Controller;

use App\Setting\Entity\CollectionGroup;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroup\DeleteCollectionGroupCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/collection-group/{id}', name: 'delete_collectiongroup', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'CollectionGroup deleted',
    content: new Model(type: CollectionGroup::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteCollectionGroupController  extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteCollectionGroupCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'collectiongroup_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
