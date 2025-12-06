<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Material;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Material\DeleteMaterialCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/deleteMaterial/{id}', name: 'delete_material', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Material deleted',
    content: new Model(type: Material::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteMaterialController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteMaterialCommand($id);

        /** @var \App\Common\Bus\Command\Command $cmd */
        $cmd = $deleteCommand;

        try {
            $response = $this->handle($cmd);
            return SuccessResponse::create(
                'material_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
