<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use DateTime;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Location\CreateLocationCommand;
use App\Contremarque\DTO\CreateLocationRequestDto;
use App\Contremarque\Entity\Location;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateLocationController extends CommandQueryController
{
    #[Route('/api/createLocation', name: 'location_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Location creation',
        content: new Model(type: Location::class)
    )]
    #[OA\RequestBody(
        description: 'Location data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'contremarqueId', type: 'integer'),
                new OA\Property(property: 'carpetTypeId', type: 'integer'),
                new OA\Property(property: 'description', type: 'string'),
                new OA\Property(property: 'quote_processed', type: 'boolean'),
                new OA\Property(property: 'quote_processing_date', type: 'datetime'),
                new OA\Property(property: 'price_min', type: 'float'),
                new OA\Property(property: 'price_max', type: 'float'),
                new OA\Property(property: 'createdAt', type: 'datetime'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateLocationRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $today = new DateTime();
        $createLocationCommand = new CreateLocationCommand(
            $requestDTO->contremarqueId,
            $requestDTO->carpetTypeId,
            $requestDTO->description,
            $requestDTO->quote_processing_date ?? '',
            $requestDTO->price_min,
            $requestDTO->price_max,
            $today->format('Y-m-d H:i:s'),
            $requestDTO->quote_processed ?? false,
        );
        $locationResponse = $this->handle($createLocationCommand);

        return SuccessResponse::create(
            'location_creation',
            $locationResponse->toArray(),
            'Location created successfully'

        );
    }
}
