<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\DominantColor\CreateDominantColorCommand;
use App\Setting\DTO\CreateDominantColorRequestDto;
use App\Setting\Entity\DominantColor;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dominant-color/create', name: 'dominant_color_creation', methods: ['POST'])]
class CreateDominantColorController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Dominant color creation',
        content: new Model(type: DominantColor::class)
    )]
    #[OA\RequestBody(
        description: 'Dominant color data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'hexCode', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateDominantColorRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateDominantColorCommand(
            $requestDTO->name,
            $requestDTO->hexCode
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'dominant_color_creation',
            $response->toArray()
        );
    }
}
