<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Quality\CreateQualityCommand;
use App\Setting\DTO\CreateQualityRequestDto;
use App\Setting\Entity\Quality;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createQuality', name: 'quality_creation', methods: ['POST'])]
class CreateQualityController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Quality creation',
        content: new Model(type: Quality::class)
    )]
    #[OA\RequestBody(
        description: 'Quality data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'weight', type: 'float', nullable: true),
                new OA\Property(property: 'velvet_standard', type: 'float', nullable: true),
                new OA\Property(
                    property: 'description',
                    type: 'array',
                    nullable: true,
                    items: new OA\Items(type: 'string')
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateQualityRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateQualityCommand(
            $requestDTO->name,
            $requestDTO->weight ?? null,
            $requestDTO->velvet_standard ?? null,
            $requestDTO->description ?? []
        );

        $qualityResponse = $this->handle($createCommand);

        return SuccessResponse::create(
            'quality_creation',
            $qualityResponse->toArray()
        );
    }
}
