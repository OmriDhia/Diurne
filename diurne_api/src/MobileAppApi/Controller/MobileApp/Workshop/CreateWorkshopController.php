<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Workshop;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\Workshop\CreateWorkshop\CreateWorkshopCommand;
use App\MobileAppApi\DTO\Workshop\CreateWorkshopRequestDto;
use App\MobileAppApi\Entity\Workshop;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateWorkshopController extends CommandQueryController
{
    #[Route('/api/mobile/workshops', name: 'create_workshop', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop creation',
        content: new Model(type: Workshop::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'carpetRnPrefix', type: 'string', nullable: true),
                new OA\Property(property: 'sampleRnPrefix', type: 'string', nullable: true),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateWorkshopRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateWorkshopCommand(
            $requestDto->name,
            $requestDto->carpetRnPrefix,
            $requestDto->sampleRnPrefix
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_workshop',
            $response->toArray(),
            'Workshop created successfully'
        );
    }
}
