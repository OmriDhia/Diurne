<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateFinishing\CreateFinishingCommand;
use App\Contremarque\DTO\CreateFinishingRequestDto;
use App\Contremarque\Entity\Finishing;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateFinishingController extends CommandQueryController
{
    #[Route('/api/customerInstruction/{customerInstructionId}/finishing/create', name: 'create_finishing', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Finishing',
        content: new Model(type: Finishing::class)
    )]
    #[OA\RequestBody(
        description: 'Finishing data',
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
        #[MapRequestPayload] CreateFinishingRequestDto $requestDTO
    ): JsonResponse {
        $createFinishingCommand = new CreateFinishingCommand(
            (int)$customerInstructionId,
            $requestDTO->fabricColor,
            $requestDTO->fringe,
            $requestDTO->withoutBanking,
            $requestDTO->noBinding,
            $requestDTO->mzCarved,
            $requestDTO->otherCarvedSignature,
            $requestDTO->standardVelvetHeight,
            $requestDTO->specialVelvetHeight
        );
        // this is CreateFinishingResponse
        $response = $this->handle($createFinishingCommand);

        return SuccessResponse::create(
            'create_finishing',
            $response->toArray(),
            'Finishing created successfully'
        );
    }
}
