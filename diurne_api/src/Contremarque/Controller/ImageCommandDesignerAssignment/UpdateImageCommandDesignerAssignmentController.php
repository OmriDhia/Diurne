<?php

namespace App\Contremarque\Controller\ImageCommandDesignerAssignment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ImageCommandDesignerAssignment\ImageCommandDesignerAssignmentResponse;
use App\Contremarque\Bus\Command\ImageCommandDesignerAssignment\UpdateImageCommandDesignerAssignmentCommand;
use App\Contremarque\DTO\UpdateImageCommandDesignerAssignmentRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateImageCommandDesignerAssignmentController extends CommandQueryController
{
    #[Route(
        '/api/image-command/assign-designer/{id}',
        name: 'assign_designer_image_command_update',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Assign image command designer update',
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
        int                                                                 $id,
        #[MapRequestPayload] UpdateImageCommandDesignerAssignmentRequestDto $requestDTO,
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $assignDesigner = new UpdateImageCommandDesignerAssignmentCommand(
            $id,
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
            'image_command_designer_assignment_update',
            $imageResponse->toArray(),
            'Image Command Designer assignment updated successfully'

        );
    }
}
