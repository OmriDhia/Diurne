<?php

namespace App\CheckingList\Controller\ShapeValidation;

use App\CheckingList\Bus\Command\CreateShapeValidation\CreateShapeValidationCommand;
use App\CheckingList\DTO\ShapeValidation\CreateShapeValidationRequestDto;
use App\CheckingList\Entity\ShapeValidation;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateShapeValidationController extends CommandQueryController
{
    #[Route('/api/shapeValidations', name: 'shape_validation_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: ShapeValidation::class))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapRequestPayload] CreateShapeValidationRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateShapeValidationCommand(
            checkingListId: $dto->checking_list_id,
            shapeValidation: $dto->shape_validation,
            realWidth: $dto->real_width,
            realLength: $dto->real_length,
            surface: $dto->surface,
            diagonalA: $dto->diagonal_a,
            diagonalB: $dto->diagonal_b,
            comment: $dto->comment,
        );
        $response = $this->handle($command);

        return SuccessResponse::create('shape_validation_created', $response->toArray(), 'Shape validation created', Response::HTTP_CREATED);
    }
}
