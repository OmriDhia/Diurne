<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialTreatment\UpdateSpecialTreatmentCommand;
use App\Setting\DTO\UpdateSpecialTreatmentRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateSpecialTreatmentController extends CommandQueryController
{
    #[Route('/api/specialTreatment/{id}', name: 'specialTreatment_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'SpecialTreatment updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateSpecialTreatmentRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'label', type: 'string'),
                new OA\Property(property: 'price', type: 'number'),
                new OA\Property(property: 'unit', type: 'string'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateSpecialTreatmentRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateSpecialTreatmentCommand(
            $id,
            $updateDto->label,
            $updateDto->price,
            $updateDto->unit,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'specialTreatment_update',
            $response->toArray(),
            "SpecialTreatment updated successfully"
        );
    }
}
