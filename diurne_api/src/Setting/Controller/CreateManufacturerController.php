<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Manufacturer\CreateManufacturerCommand;
use App\Setting\DTO\CreateManufacturerRequestDto;
use App\Setting\Entity\Manufacturer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createManufacturer', name: 'manufacturer_creation', methods: ['POST'])]
class CreateManufacturerController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Manufacturer creation',
        content: new Model(type: Manufacturer::class)
    )]
    #[OA\RequestBody(
        description: 'Manufacturer data',
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
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateManufacturerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateManufacturerCommand(
            $requestDTO->name,
            $requestDTO->company,
            $requestDTO->carpetPrefix,
            $requestDTO->samplePrefix,
            $requestDTO->creditAmount,
            $requestDTO->complexityBonus,
            $requestDTO->oversizeBonus,
            $requestDTO->oversizeMohaiBonus,
            $requestDTO->multiLevelBonus,
            $requestDTO->specialFormBonus,
            $requestDTO->carpetCountryID,
            $requestDTO->currencyID
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'manufacturer_creation',
            $response->toArray()
        );
    }
}
