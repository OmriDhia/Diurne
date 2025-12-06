<?php

namespace App\CheckingList\Controller\ShapeValidation;

use App\CheckingList\Bus\Command\DeleteShapeValidation\DeleteShapeValidationCommand;
use App\CheckingList\Entity\ShapeValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteShapeValidationController extends CommandQueryController
{
    #[Route('/api/shapeValidations/{id}', name: 'shape_validation_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: ShapeValidation::class))]
    #[OA\Parameter(name: 'id', description: 'Shape validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteShapeValidationCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create('shape_validation_deleted', $response->toArray(), 'Shape validation deleted', Response::HTTP_OK);
    }
}
