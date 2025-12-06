<?php

namespace App\Contremarque\Controller;

use DateTime;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateDesignerAssignment\CreateDesignerAssignmentCommand;
use App\Contremarque\DTO\CreateDesignerAssignmentRequestDto;
use App\Contremarque\Entity\DesignerAssignment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateDesignerAssignmentController extends CommandQueryController
{
    #[Route('/api/carpetDesignOrders/{carpetDesignOrderId}/designerAssignment', name: 'create_designer_assignment', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Designer Assignment',
        content: new Model(type: DesignerAssignment::class)
    )]
    #[OA\RequestBody(
        description: 'Designer Assignment data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'designerId', type: 'integer'),
                new OA\Property(property: 'inProgress', type: 'boolean'),
                new OA\Property(property: 'stopped', type: 'boolean'),
                new OA\Property(property: 'done', type: 'boolean'),
                new OA\Property(property: 'dateFrom', type: 'string', format: 'date-time'),
                new OA\Property(property: 'dateTo', type: 'string', format: 'date-time'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int                                                     $carpetDesignOrderId,
        #[MapRequestPayload] CreateDesignerAssignmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createDesignerAssignmentCommand = new CreateDesignerAssignmentCommand(
            $carpetDesignOrderId,
            $requestDTO->designerId,
            $requestDTO->inProgress,
            $requestDTO->stopped,
            $requestDTO->done,
            (null !== $requestDTO->dateTo && '' !== $requestDTO->dateTo) ? ((new DateTime($requestDTO->dateTo))->format('Y-m-d H:i:s')) : ('' == $requestDTO->dateTo ? (new DateTime('2100-01-01 00:00:00'))->format('Y-m-d H:i:s') : null),

        );

        $designerAssignmentResponse = $this->handle($createDesignerAssignmentCommand);

        return SuccessResponse::create(
            'create_designer_assignment',
            $designerAssignmentResponse->toArray(),
            'Designer Assignment created',

        );
    }
}
