<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Location\UpdateLocationCommand;
use App\Contremarque\DTO\UpdateLocationRequestDto;
use App\Contremarque\Entity\Location;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateLocationController extends CommandQueryController
{
    #[Route('/api/updateLocation/{id}', name: 'location_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Location update',
        content: new OA\JsonContent(
            ref: new Model(type: Location::class)
        )
    )]
    #[OA\RequestBody(
        description: 'Location data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'carpetTypeId', type: 'integer'),
                new OA\Property(property: 'description', type: 'string'),
                new OA\Property(property: 'quoteProcessed', type: 'boolean'),
                new OA\Property(property: 'quoteProcessingDate', type: 'string', format: 'date-time'),
                new OA\Property(property: 'priceMin', type: 'number', format: 'float'),
                new OA\Property(property: 'priceMax', type: 'number', format: 'float'),
                new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time'),
                new OA\Property(property: 'contremarqueId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateLocationRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateLocationCommand = new UpdateLocationCommand(
            $id,
            $requestDTO->carpetTypeId,
            $requestDTO->description,
            $requestDTO->quoteProcessed,
            $requestDTO->quoteProcessingDate,
            $requestDTO->priceMin,
            $requestDTO->priceMax,
            $requestDTO->updatedAt,
            $requestDTO->contremarqueId
        );

        $locationResponse = $this->handle($updateLocationCommand);

        return SuccessResponse::create(
            'location_update',
            $locationResponse->toArray(),
            'Location updated successfully'
        );
    }
}
