<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateFinishing\UpdateFinishingCommand;
use App\Contremarque\DTO\UpdateFinishingRequestDto;
use App\Contremarque\Entity\Finishing;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateFinishingController extends CommandQueryController
{
    #[Route('/api/customerInstruction/{customerInstructionId}/finishing/{id}/update', name: 'update_finishing', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update Finishing',
        content: new Model(type: Finishing::class)
    )]
    #[OA\RequestBody(
        description: 'Finishing data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'fabricColor', type: 'string'),
                new OA\Property(property: 'fringe', type: 'boolean'),
                new OA\Property(property: 'withoutBanking', type: 'boolean'),
                new OA\Property(property: 'noBinding', type: 'boolean'),
                new OA\Property(property: 'mzCarved', type: 'boolean'),
                new OA\Property(property: 'otherCarvedSignature', type: 'string'),
                new OA\Property(property: 'standardVelvetHeight', type: 'string', format: 'decimal'),
                new OA\Property(property: 'specialVelvetHeight', type: 'string', format: 'decimal'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $customerInstructionId,
        $id,
        #[MapRequestPayload] UpdateFinishingRequestDto $requestDTO
    ): JsonResponse {
        $updateFinishingCommand = new UpdateFinishingCommand(
            (int) $customerInstructionId,
            (int) $id,
            $requestDTO->fabricColor,
            $requestDTO->fringe,
            $requestDTO->withoutBanking,
            $requestDTO->noBinding,
            $requestDTO->mzCarved,
            $requestDTO->otherCarvedSignature,
            $requestDTO->standardVelvetHeight,
            $requestDTO->specialVelvetHeight
        );

        // Handle the command
        $response = $this->handle($updateFinishingCommand);

        // Return the response
        return SuccessResponse::create(
            'update_finishing',
            $response->toArray(),
            'Finishing updated successfully'
        );
    }
}
