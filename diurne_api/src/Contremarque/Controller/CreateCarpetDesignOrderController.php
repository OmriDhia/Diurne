<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetDesignOrder\CreateCarpetDesignOrderCommand;
use App\Contremarque\DTO\CreateCarpetDesignOrderRequestDto;
use App\Contremarque\Entity\CarpetDesignOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCarpetDesignOrderController extends CommandQueryController
{
    #[Route('/api/projectDi/{projectDiId}/carpet-design-order', name: 'carpet_design_order_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Carpet Design Order',
        content: new Model(type: CarpetDesignOrder::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet Design Order data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'location_id', type: 'integer'),
                new OA\Property(property: 'designer_assignments', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'status_id', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $projectDiId,
        #[MapRequestPayload] CreateCarpetDesignOrderRequestDto $requestDTO
    ): JsonResponse
    {
        $createCarpetDesignOrderCommand = new CreateCarpetDesignOrderCommand(
            (int)$projectDiId,
            $requestDTO->location_id,
            $requestDTO->designer_assignments,
            (int)$requestDTO->status_id,
            $requestDTO->modelName,
            $requestDTO->variation,
            $requestDTO->jpeg,
            $requestDTO->impression,
            $requestDTO->impressionBarreDeLaine
        );

        $response = $this->handle($createCarpetDesignOrderCommand);

        return SuccessResponse::create(
            'carpet_design_order_created',
            $response->toArray()
        );
    }
}
