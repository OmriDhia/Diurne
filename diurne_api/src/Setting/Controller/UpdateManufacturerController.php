<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Manufacturer\UpdateManufacturerCommand;
use App\Setting\DTO\UpdateManufacturerRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateManufacturerController extends CommandQueryController
{
    #[Route('/api/updateManufacturer/{id}', name: 'manufacturer_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Manufacturer updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateManufacturerRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'company'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'company', type: 'string'),
                new OA\Property(property: 'carpetPrefix', type: 'string'),
                new OA\Property(property: 'samplePrefix', type: 'string'),
                new OA\Property(property: 'creditAmount', type: 'float'),
                new OA\Property(property: 'complexityBonus', type: 'float'),
                new OA\Property(property: 'oversizeBonus', type: 'float'),
                new OA\Property(property: 'oversizeMohaiBonus', type: 'float'),
                new OA\Property(property: 'multiLevelBonus', type: 'float'),
                new OA\Property(property: 'specialFormBonus', type: 'float'),
                new OA\Property(property: 'carpetCountryID', type: 'int'),
                new OA\Property(property: 'currencyID', type: 'int'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateManufacturerRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateManufacturerCommand(
            $id,
            $updateDto->name,
            $updateDto->company,
            $updateDto->carpetPrefix,
            $updateDto->samplePrefix,
            $updateDto->creditAmount,
            $updateDto->complexityBonus,
            $updateDto->oversizeBonus,
            $updateDto->oversizeMohaiBonus,
            $updateDto->multiLevelBonus,
            $updateDto->specialFormBonus,
            $updateDto->carpetCountryID,
            $updateDto->currencyID,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'manufacturer_update',
            $response->toArray()
        );
    }
}
