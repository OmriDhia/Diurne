<?php

namespace App\CheckingList\Controller\ShapeValidation;

use App\CheckingList\Bus\Query\GetShapeValidationById\GetShapeValidationByIdQuery;
use App\CheckingList\Entity\ShapeValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetShapeValidationByIdController extends CommandQueryController
{
    #[Route('/api/shapeValidations/{id}', name: 'shape_validation_by_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Item', content: new Model(type: ShapeValidation::class))]
    #[OA\Parameter(name: 'id', description: 'Shape validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetShapeValidationByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('shape_validation_item', $response->toArray());
    }
}
