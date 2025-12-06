<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetType\CreateCarpetTypeCommand;
use App\Contremarque\DTO\CreateCarpetTypeRequestDto;
use App\Contremarque\Entity\CarpetType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCarpetTypeController extends CommandQueryController
{
    #[Route('/api/createCarpetType', name: 'carpetType_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'CarpetType creation',
        content: new Model(type: CarpetType::class)
    )]
    #[OA\RequestBody(
        description: 'CarpetType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'integer'),
            ]
        ))]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateCarpetTypeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCarpetTypeCommand = new CreateCarpetTypeCommand($requestDTO->name);
        $carpetTypeResponse = $this->handle($createCarpetTypeCommand);

        return SuccessResponse::create(
            'carpetType_creation',
            $carpetTypeResponse->toArray()
        );
    }
}
