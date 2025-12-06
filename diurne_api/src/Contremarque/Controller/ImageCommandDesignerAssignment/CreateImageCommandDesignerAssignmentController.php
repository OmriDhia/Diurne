<?php

namespace App\Contremarque\Controller\ImageCommandDesignerAssignment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ImageCommandDesignerAssignment\ImageCommandDesignerAssignmentCommand;
use App\Contremarque\Bus\Command\ImageCommandDesignerAssignment\ImageCommandDesignerAssignmentResponse;
use App\Contremarque\DTO\CreateImageCommandDesignerAssignmentRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateImageCommandDesignerAssignmentController extends CommandQueryController
{
    #[Route(
        '/api/image-command/assign-designer',
        name: 'assign_designer_image_command',
        methods: ['POST']
    )]
    #[OA\Response(
        response: 200,
        description: 'Assign image command designer',
        content: new Model(type: ImageCommandDesignerAssignmentResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageCommandId', type: 'integer'),
                new OA\Property(property: 'designerId', type: 'integer'),
                new OA\Property(property: 'from', type: 'string', format: 'date-time'),
                new OA\Property(property: 'to', type: 'string', format: 'date-time'),
                new OA\Property(property: 'inProgress', type: 'boolean'),
                new OA\Property(property: 'stopped', type: 'boolean'),
                new OA\Property(property: 'reasonForStopping', type: 'string'),
                new OA\Property(property: 'done', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateImageCommandDesignerAssignmentRequestDto $requestDTO,
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $assignDesigner = new ImageCommandDesignerAssignmentCommand(
            $requestDTO->imageCommandId,
            $requestDTO->designerId,
            $requestDTO->from,
            $requestDTO->to,
            $requestDTO->inProgress,
            $requestDTO->stopped,
            $requestDTO->reasonForStopping,
            $requestDTO->done
        );

        $imageResponse = $this->handle($assignDesigner);

        return SuccessResponse::create(
            'image_command_designer_assignment',
            $imageResponse->toArray()

        );
    }
}
