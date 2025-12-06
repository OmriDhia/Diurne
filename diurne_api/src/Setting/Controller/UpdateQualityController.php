<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Quality\UpdateQualityCommand;
use App\Setting\DTO\UpdateQualityRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/updateQuality/{id}', name: 'update_quality', methods: ['PUT'])]
class UpdateQualityController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Quality updated',
        content: new Model(type: SuccessResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Quality not found'
    )]
    #[OA\RequestBody(
        description: 'Quality data',
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'weight', type: 'string', nullable: true),
                new OA\Property(property: 'velvet_standard', type: 'string', nullable: true),
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
    public function __invoke(int $id, #[MapRequestPayload] UpdateQualityRequestDto $requestDTO): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $updateCommand = new UpdateQualityCommand(
            $id,
            $requestDTO->name,
            $requestDTO->weight ?? null,
            $requestDTO->velvet_standard ?? null,
            $requestDTO->description ?? []
        );
        $qualityResponse = $this->handle($updateCommand);

        return SuccessResponse::create(
            'update_quality',
            $qualityResponse->toArray()
        );
    }
}
