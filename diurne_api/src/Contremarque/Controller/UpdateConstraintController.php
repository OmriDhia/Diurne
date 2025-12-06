<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateConstraint\UpdateConstraintCommand;
use App\Contremarque\DTO\UpdateConstraintRequestDto;
use App\Contremarque\Entity\CustomerConstraint;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateConstraintController extends CommandQueryController
{
    #[Route('/api/customerInstruction/{customerInstructionId}/constraints/{id}/update', name: 'update_constraint', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update Constraint',
        content: new Model(type: CustomerConstraint::class)
    )]
    #[OA\RequestBody(
        description: 'Constraint data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'transmittedPlan', type: 'boolean'),
                new OA\Property(property: 'libTransmittedPlan', type: 'string'),
                new OA\Property(property: 'pit', type: 'boolean'),
                new OA\Property(property: 'libPit', type: 'string'),
                new OA\Property(property: 'lineHeight', type: 'boolean'),
                new OA\Property(property: 'libLineHeight', type: 'string'),
                new OA\Property(property: 'specialThickness', type: 'boolean'),
                new OA\Property(property: 'libSpecialThickness', type: 'string'),
                new OA\Property(property: 'otherCarpetInTheRoom', type: 'boolean'),
                new OA\Property(property: 'libOtherCarpetInTheRoom', type: 'string'),
                new OA\Property(property: 'miniLength', type: 'boolean'),
                new OA\Property(property: 'maxiLength', type: 'boolean'),
                new OA\Property(property: 'miniWidth', type: 'boolean'),
                new OA\Property(property: 'maxiWidth', type: 'boolean'),
                new OA\Property(property: 'dstWallHeight', type: 'string'),
                new OA\Property(property: 'dstWallDown', type: 'string'),
                new OA\Property(property: 'dstWallLeft', type: 'string'),
                new OA\Property(property: 'dstWallRight', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $customerInstructionId,
        $id,
        #[MapRequestPayload] UpdateConstraintRequestDto $requestDTO
    ): JsonResponse {
        $updateConstraintCommand = new UpdateConstraintCommand(
            (int) $customerInstructionId,
            (int) $id,
            $requestDTO->transmittedPlan,
            $requestDTO->libTransmittedPlan,
            $requestDTO->pit,
            $requestDTO->libPit,
            $requestDTO->lineHeight,
            $requestDTO->libLineHeight,
            $requestDTO->specialThickness,
            $requestDTO->libSpecialThickness,
            $requestDTO->otherCarpetInTheRoom,
            $requestDTO->libOtherCarpetInTheRoom,
            $requestDTO->miniLength,
            $requestDTO->maxiLength,
            $requestDTO->miniWidth,
            $requestDTO->maxiWidth,
            $requestDTO->dstWallHeight,
            $requestDTO->dstWallDown,
            $requestDTO->dstWallLeft,
            $requestDTO->dstWallRight
        );

        $response = $this->handle($updateConstraintCommand);

        return SuccessResponse::create(
            'update_constraint',
            $response->toArray(),
            'Constraint updated successfully'

        );
    }
}
