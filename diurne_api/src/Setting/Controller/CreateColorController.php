<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Color\CreateColorCommand;
use App\Setting\DTO\CreateColorRequestDto;
use App\Setting\Entity\Color;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/color/create', name: 'color_creation', methods: ['POST'])]
class CreateColorController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Color creation',
        content: new Model(type: Color::class)
    )]
    #[OA\RequestBody(
        description: 'Color data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'reference', type: 'string'),
                new OA\Property(property: 'hexCode', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateColorRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateColorCommand(
            $requestDTO->reference,
            $requestDTO->hexCode
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'color_creation',
            $response->toArray()
        );
    }
}
