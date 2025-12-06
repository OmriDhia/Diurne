<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use InvalidArgumentException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\ErrorResponse;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetDesignOrder\UpdateCarpetDesignOrderCommand;
use App\Contremarque\DTO\UpdateCarpetDesignOrderRequestDto;
use App\Contremarque\Entity\CarpetDesignOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCarpetDesignOrderController extends CommandQueryController
{
    #[Route('/api/carpet-design-order/{id}', name: 'carpet_design_order_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update Carpet Design Order',
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
        int                                                    $id,
        #[MapRequestPayload] UpdateCarpetDesignOrderRequestDto $requestDTO
    ): JsonResponse
    {
        $updateCarpetDesignOrderCommand = new UpdateCarpetDesignOrderCommand(
            $id,
            $requestDTO->location_id,
            $requestDTO->designer_assignments,
            $requestDTO->status_id,
            $requestDTO->modelName,
            $requestDTO->variation,
            $requestDTO->jpeg,
            $requestDTO->impression,
            $requestDTO->impressionBarreDeLaine
        );


        try {
            $response = $this->handle($updateCarpetDesignOrderCommand);
            return SuccessResponse::create(
                'carpet_design_order_updated',
                $response->toArray(),
                (string)empty($response->toArray()['errors'])

            );
        } catch (InvalidArgumentException $e) {
            return ErrorResponse::response(
                'carpet_design_order_update_failed',
                ['errors' => [$e->getMessage()]],
                $e->getMessage(),
                'error',
                400
            );
        }
    }
}
