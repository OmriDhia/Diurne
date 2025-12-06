<?php

namespace App\Setting\Controller;

use App\Setting\Entity\SpecialShape;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialShape\DeleteSpecialShapeCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/specialShape/{id}', name: 'delete_specialshape', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'SpecialShape deleted',
    content: new Model(type: SpecialShape::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteSpecialShapeController  extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteSpecialShapeCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'specialshape_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
