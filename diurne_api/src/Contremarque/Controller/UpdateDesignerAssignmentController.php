<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use DateTime;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateDesignerAssignment\UpdateDesignerAssignmentCommand;
use App\Contremarque\Bus\Command\UpdateDesignerAssignment\UpdateDesignerAssignmentResponse;
use App\Contremarque\DTO\UpdateDesignerAssignmentRequestDto;
use App\Contremarque\Entity\DesignerAssignment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateDesignerAssignmentController extends CommandQueryController
{
    #[Route('/api/designerAssignments/{id}', name: 'update_designer_assignment', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update DesignerAssignment',
        content: new Model(type: DesignerAssignment::class)
    )]
    #[OA\RequestBody(
        description: 'Update DesignerAssignment data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'dateFrom', type: 'string', format: 'date-time'),
                new OA\Property(property: 'dateTo', type: 'string', format: 'date-time'),
                new OA\Property(property: 'inProgress', type: 'boolean'),
                new OA\Property(property: 'stopped', type: 'boolean'),
                new OA\Property(property: 'done', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateDesignerAssignmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new UpdateDesignerAssignmentCommand(
            $id,
            $requestDTO->dateFrom ? (new DateTime($requestDTO->dateFrom))->format('Y-m-d H:i:s') : null,
            (null !== $requestDTO->dateTo && '' !== $requestDTO->dateTo) ? ((new DateTime($requestDTO->dateTo))->format('Y-m-d H:i:s')) : ('' == $requestDTO->dateTo ? (new DateTime('2100-01-01 00:00:00'))->format('Y-m-d H:i:s') : null),
            $requestDTO->inProgress,
            $requestDTO->stopped,
            $requestDTO->done
        );

        /** @var UpdateDesignerAssignmentResponse $response */
        $response = $this->handle($command);

        return SuccessResponse::create(
            'update_designer_assignment',
            $response->toArray(),
            'DesignerAssignment updated'
        );
    }
}
