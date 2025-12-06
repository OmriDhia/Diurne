<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\MaterialPrice\CreateMaterialPriceCommand;
use App\Setting\DTO\CreateMaterialPriceRequestDto;
use App\Setting\Entity\MaterialPrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createMaterialPrice', name: 'materialPrice_creation', methods: ['POST'])]
class CreateMaterialPriceController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'MaterialPrice creation',
        content: new Model(type: MaterialPrice::class)
    )]
    #[OA\RequestBody(
        description: 'MaterialPrice data',
        content: new OA\JsonContent(
            required: ['materialId'],
            properties: [
                new OA\Property(property: 'materialId', type: 'integer'),
                new OA\Property(property: 'publicPrice', type: 'float', nullable: true),
                new OA\Property(property: 'bigProjectPrice', type: 'float', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateMaterialPriceRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateMaterialPriceCommand(
            $requestDTO->materialId,
            $requestDTO->publicPrice,
            $requestDTO->bigProjectPrice,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'materialPrice_creation',
            $response->toArray()
        );
    }
}
