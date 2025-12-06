<?php

namespace App\Setting\Controller;

use App\Setting\Entity\ImageType;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ImageType\DeleteImageTypeCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/image-types/{id}', name: 'delete_imagetype', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'ImageType deleted',
    content: new Model(type: ImageType::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteImageTypeController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteImageTypeCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'imagetype_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
