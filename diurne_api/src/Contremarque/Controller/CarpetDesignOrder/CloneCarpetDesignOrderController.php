<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\CarpetDesignOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CloneCarpetDesignOrder\CloneCarpetDesignOrderCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CloneCarpetDesignOrderController extends CommandQueryController
{
    #[Route(
        path: '/api/cloneCarpetDesignOrders/{carpetDesignOrderId}',
        name: 'clone_carpet_design_order',
        methods: ['POST']
    )]
    #[OA\Post(
        path: '/api/cloneCarpetDesignOrders/{carpetDesignOrderId}',
        tags: ['Contremarque'],
        summary: 'Clone a Carpet Design Order',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Carpet Design Order cloned'
            )
        ]
    )]
    public function __invoke(
        int $carpetDesignOrderId
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CloneCarpetDesignOrderCommand($carpetDesignOrderId);

        $response = $this->handle($command);

        return SuccessResponse::create('carpet_design_order_cloned', $response->toArray());
    }
}
