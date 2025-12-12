<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Workshop;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\Workshop\UpdateWorkshop\UpdateWorkshopCommand;
use App\MobileAppApi\DTO\Workshop\UpdateWorkshopRequestDto;
use App\MobileAppApi\Entity\Workshop;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateWorkshopController extends CommandQueryController
{
    #[Route('/api/mobile/workshops/{id}', name: 'update_workshop', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'Update Workshop',
        content: new Model(type: Workshop::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'carpetRnPrefix', type: 'string', nullable: true),
                new OA\Property(property: 'sampleRnPrefix', type: 'string', nullable: true),
            ]
        )
    )]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateWorkshopRequestDto $requestDto
    ): JsonResponse {
        $command = new UpdateWorkshopCommand(
            $id,
            $requestDto->name,
            $requestDto->carpetRnPrefix,
            $requestDto->sampleRnPrefix
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'update_workshop',
            $response->toArray(),
            'Workshop updated successfully'
        );
    }
}
