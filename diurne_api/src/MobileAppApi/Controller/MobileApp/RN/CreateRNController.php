<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\RN;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\RN\CreateRN\CreateRNCommand;
use App\MobileAppApi\DTO\RN\CreateRNRequestDto;
use App\MobileAppApi\Entity\RN;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateRNController extends CommandQueryController
{
    #[Route('/api/mobile/rn', name: 'create_rn', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'RN creation',
        content: new Model(type: RN::class)
    )]
    #[OA\RequestBody(
        description: 'RN data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnNumber', type: 'string'),
                new OA\Property(property: 'workshopId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateRNRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateRNCommand(
            $requestDto->rnNumber,
            $requestDto->workshopId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_rn',
            $response->toArray(),
            'RN created successfully'
        );
    }
}
