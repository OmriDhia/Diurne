<?php

namespace App\CheckingList\Controller\LayersValidation;

use App\CheckingList\Bus\Command\DeleteLayersValidation\DeleteLayersValidationCommand;
use App\CheckingList\Entity\LayersValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteLayersValidationController extends CommandQueryController
{
    #[Route('/api/layersValidations/{id}', name: 'layers_validation_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: LayersValidation::class))]
    #[OA\Parameter(name: 'id', description: 'Layers validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteLayersValidationCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create('layers_validation_deleted', $response->toArray(), 'Layers validation deleted', Response::HTTP_OK);
    }
}
