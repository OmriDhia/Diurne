<?php

namespace App\CheckingList\Controller\LayersValidation;

use App\CheckingList\Bus\Query\GetLayersValidationById\GetLayersValidationByIdQuery;
use App\CheckingList\Entity\LayersValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetLayersValidationByIdController extends CommandQueryController
{
    #[Route('/api/layersValidations/{id}', name: 'layers_validation_by_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Item', content: new Model(type: LayersValidation::class))]
    #[OA\Parameter(name: 'id', description: 'Layers validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetLayersValidationByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('layers_validation_item', $response->toArray());
    }
}
