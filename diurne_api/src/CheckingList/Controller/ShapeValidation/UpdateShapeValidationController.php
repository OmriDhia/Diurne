<?php

namespace App\CheckingList\Controller\ShapeValidation;

use App\CheckingList\Bus\Command\UpdateShapeValidation\UpdateShapeValidationCommand;
use App\CheckingList\DTO\ShapeValidation\UpdateShapeValidationRequestDto;
use App\CheckingList\Entity\ShapeValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateShapeValidationController extends CommandQueryController
{
    #[Route('/api/shapeValidations/{id}', name: 'shape_validation_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: ShapeValidation::class))]
    #[OA\RequestBody(
        description: 'Shape validation update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'shape_relevant', type: 'boolean'),
                new OA\Property(property: 'shape_validation', type: 'boolean'),
                new OA\Property(property: 'shape_seen', type: 'boolean'),
                new OA\Property(property: 'real_width', type: 'string'),
                new OA\Property(property: 'real_length', type: 'string'),
                new OA\Property(property: 'surface', type: 'string'),
                new OA\Property(property: 'diagonal_a', type: 'string'),
                new OA\Property(property: 'diagonal_b', type: 'string'),
                new OA\Property(property: 'comment', type: 'string')
            ]
        )
    )]
    #[OA\Parameter(name: 'id', description: 'Shape validation id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateShapeValidationRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateShapeValidationCommand(
            id: $id,
            shapeRelevant: $dto->shape_relevant,
            shapeValidation: $dto->shape_validation,
            shapeSeen: $dto->shape_seen,
            realWidth: $dto->real_width,
            realLength: $dto->real_length,
            surface: $dto->surface,
            diagonalA: $dto->diagonal_a,
            diagonalB: $dto->diagonal_b,
            comment: $dto->comment,
        );
        $response = $this->handle($command);

        return SuccessResponse::create('shape_validation_updated', $response->toArray(), 'Shape validation updated');
    }
}
