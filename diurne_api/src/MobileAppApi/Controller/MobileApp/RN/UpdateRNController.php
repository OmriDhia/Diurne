<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\RN;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\RN\UpdateRN\UpdateRNCommand;
use App\MobileAppApi\DTO\RN\UpdateRNRequestDto;
use App\MobileAppApi\Entity\RN;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateRNController extends CommandQueryController
{
    #[Route('/api/mobile/rn/{id}', name: 'update_rn', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'Update RN',
        content: new Model(type: RN::class)
    )]
    #[OA\RequestBody(
        description: 'RN data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnNumber', type: 'string'),
                new OA\Property(property: 'workshopId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateRNRequestDto $requestDto
    ): JsonResponse {
        $command = new UpdateRNCommand(
            $id,
            $requestDto->rnNumber,
            $requestDto->workshopId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'update_rn',
            $response->toArray(),
            'RN updated successfully'
        );
    }
}
