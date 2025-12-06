<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateValidatedSample\CreateValidatedSampleCommand;
use App\Contremarque\DTO\CreateValidatedSampleRequestDto;
use App\Contremarque\Entity\ValidatedSample;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateValidatedSampleController extends CommandQueryController
{
    #[Route('/api/customerInstruction/{customerInstructionId}/validated-sample/create', name: 'create_validated_sample', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Validated Sample',
        content: new Model(type: ValidatedSample::class)
    )]
    #[OA\RequestBody(
        description: 'Validated Sample data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnValidatedSample', type: 'string'),
                new OA\Property(property: 'color', type: 'boolean'),
                new OA\Property(property: 'libColor', type: 'string'),
                new OA\Property(property: 'velvet', type: 'boolean'),
                new OA\Property(property: 'libVelvet', type: 'string'),
                new OA\Property(property: 'material', type: 'boolean'),
                new OA\Property(property: 'libMaterial', type: 'string'),
                new OA\Property(property: 'customerNoteOnSample', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $customerInstructionId,
        #[MapRequestPayload] CreateValidatedSampleRequestDto $requestDTO
    ): JsonResponse {
        // Create the command based on the DTO data
        $createValidatedSampleCommand = new CreateValidatedSampleCommand(
            (int)$customerInstructionId,
            $requestDTO->rnValidatedSample,
            $requestDTO->color,
            $requestDTO->libColor,
            $requestDTO->velvet,
            $requestDTO->libVelvet,
            $requestDTO->material,
            $requestDTO->libMaterial,
            $requestDTO->customerNoteOnSample
        );

        // Handle the command
        $response = $this->handle($createValidatedSampleCommand);

        // Return a successful response with the data
        return SuccessResponse::create(
            'create_validated_sample',
            $response->toArray(),
            'Validated Sample created successfully',

        );
    }
}
