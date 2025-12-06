<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Color;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Color\DeleteColorCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/color/{id}', name: 'delete_color', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Color deleted',
    content: new Model(type: Color::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteColorController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteColorCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'color_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
