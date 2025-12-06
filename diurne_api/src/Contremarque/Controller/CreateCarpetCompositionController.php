<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\CreateCarpetCompositionCommand;
use App\Contremarque\DTO\CreateCarpetCompositionRequestDto;
use App\Contremarque\Entity\CarpetComposition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/CarpetSpecification/{carpetSpecificationId}/CarpetComposition/create', name: 'carpetComposition_creation', methods: ['POST'])]
class CreateCarpetCompositionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'CarpetComposition creation',
        content: new Model(type: CarpetComposition::class)
    )]
    #[OA\RequestBody(
        description: 'CarpetComposition data',
        content: new OA\JsonContent(
            required: ['threadCount', 'layerCount'],
            properties: [
                new OA\Property(property: 'trame', type: 'string', nullable: true),
                new OA\Property(property: 'threadCount', type: 'int'),
                new OA\Property(property: 'layerCount', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $carpetSpecificationId,
        #[MapRequestPayload] CreateCarpetCompositionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCarpetCompositionCommand(
            (int) $carpetSpecificationId,
            $requestDTO->trame ?? null,
            $requestDTO->threadCount,
            $requestDTO->layerCount,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'carpetComposition_creation',
            $response->toArray()
        );
    }
}
