<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Bus\Command\Command;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\TriggerStatus\TriggerStatusCommand;
use App\Contremarque\DTO\TriggerStatusRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller to handle status toggling of a Quote Detail.
 */
class TriggerStatusController extends CommandQueryController
{
    #[Route('/api/triggerStatus', name: 'trigger_status', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Toggles the status of a quote detail',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'status', type: 'string', example: 'success')]
        )
    )]
    #[OA\RequestBody(
        description: 'Status toggle data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'quoteDetailId', type: 'integer'),
                new OA\Property(property: 'newStatus', type: 'boolean', example: 'true'),
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        #[MapRequestPayload] TriggerStatusRequestDto $requestDTO
    ): JsonResponse {
        // Check permissions
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }
        $triggerStatusCommand = new TriggerStatusCommand(
            $requestDTO->quoteDetailId,
            $requestDTO->newStatus
        );
        // Handle the command
        $response = $this->handle($triggerStatusCommand);

        return SuccessResponse::create(
            'trigger_status',
            [
                'status' => 'success',
                $response->toArray(),
            ],

        );
    }
}
