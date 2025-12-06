<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation\CreateCarpetDesignOrderVariationCommand;
use App\Contremarque\DTO\CreateCarpetDesignOrderVariationRequestDto;
use App\Contremarque\Entity\CarpetDesignOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createCarpetDesignOrderVariation', name: 'create_carpet_design_order_variation', methods: ['POST'])]
class CreateCarpetDesignOrderVariationController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Carpet Design Order Variation creation',
        content: new Model(type: CarpetDesignOrder::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet Design Order Variation data',
        content: new OA\JsonContent(
            required: ['orderId', 'model'],
            properties: [
                new OA\Property(property: 'orderId', type: 'integer', description: 'The ID of the carpet design order'),
                new OA\Property(property: 'variation', type: 'string', nullable: true, description: 'The variation of the carpet design order (optional)'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateCarpetDesignOrderVariationRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCarpetDesignOrderVariationCommand(
            $requestDTO->orderId,
            $requestDTO->variation
        );


        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'create_carpet_design_order_variation',
            $response->toArray()
        );
    }
}
