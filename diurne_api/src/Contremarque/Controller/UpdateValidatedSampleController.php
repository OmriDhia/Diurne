<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateValidatedSample\UpdateValidatedSampleCommand;
use App\Contremarque\DTO\UpdateValidatedSampleRequestDto;
use App\Contremarque\Entity\ValidatedSample;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateValidatedSampleController extends CommandQueryController
{
    #[Route('/api/customerInstruction/{customerInstructionId}/validatedSamples/{id}/update', name: 'update_validated_sample', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update ValidatedSample',
        content: new Model(type: ValidatedSample::class)
    )]
    #[OA\RequestBody(
        description: 'ValidatedSample data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnValidatedSample', type: 'string', nullable: true),
                new OA\Property(property: 'color', type: 'boolean'),
                new OA\Property(property: 'libColor', type: 'string', nullable: true),
                new OA\Property(property: 'velvet', type: 'boolean'),
                new OA\Property(property: 'libVelvet', type: 'string'),
                new OA\Property(property: 'material', type: 'boolean'),
                new OA\Property(property: 'libMaterial', type: 'string', nullable: true),
                new OA\Property(property: 'customerNoteOnSample', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $customerInstructionId,
        $id,
        #[MapRequestPayload] UpdateValidatedSampleRequestDto $requestDTO
    ): JsonResponse {
        // Creating the command to update the ValidatedSample
        $updateValidatedSampleCommand = new UpdateValidatedSampleCommand(
            (int) $customerInstructionId,
            (int) $id,
            $requestDTO->rnValidatedSample,
            $requestDTO->color,
            $requestDTO->libColor,
            $requestDTO->velvet,
            $requestDTO->libVelvet,
            $requestDTO->material,
            $requestDTO->libMaterial,
            $requestDTO->customerNoteOnSample
        );

        // Handling the command to update the entity
        $response = $this->handle($updateValidatedSampleCommand);

        // Returning success response
        return SuccessResponse::create(
            'update_validated_sample',
            $response->toArray()
        );
    }
}
