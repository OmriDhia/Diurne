<?php

namespace App\Setting\Controller;

use App\Setting\Entity\DominantColor;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\DominantColor\DeleteDominantColorCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dominant-color/{id}', name: 'delete_dominantcolor', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'DominantColor deleted',
    content: new Model(type: DominantColor::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteDominantColorController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteDominantColorCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'dominantcolor_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
