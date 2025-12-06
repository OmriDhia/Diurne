<?php

declare(strict_types=1);

namespace App\Setting\Controller\TarifTexture;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TarifTexture\CreateTarifTextureCommand;
use App\Setting\DTO\CreateTarifTextureRequestDto;
use App\Setting\Entity\TarifTexture;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tarifTexture/create', name: 'tariftexture_creation', methods: ['POST'])]
class CreateTarifTextureController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'TarifTexture creation',
        content: new Model(type: TarifTexture::class)
    )]
    #[OA\RequestBody(
        description: 'TarifTexture data',
        content: new OA\JsonContent(
            required: ['year'],
            properties: [
                new OA\Property(property: 'year', type: 'string')
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateTarifTextureRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateTarifTextureCommand($requestDTO->year);

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'tariftexture_creation',
            $response->toArray()
        );
    }
}

