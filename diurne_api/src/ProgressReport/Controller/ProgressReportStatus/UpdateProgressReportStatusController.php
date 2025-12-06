<?php

namespace App\ProgressReport\Controller\ProgressReportStatus;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReportStatus\UpdateProgressReportStatus\UpdateProgressReportStatusCommand;
use App\ProgressReport\DTO\ProgressReportStatus\UpdateProgressReportStatusRequestDto;
use App\ProgressReport\Entity\ProgressReportStatus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Update a progress report status.
 */
class UpdateProgressReportStatusController extends CommandQueryController
{
    #[Route('/api/progressReportStatus/{id}', name: 'progress_report_status_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: ProgressReportStatus::class))]
    #[OA\RequestBody(
        description: 'Update progress report status data',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'status', type: 'string', example: 'done')])
    )]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateProgressReportStatusRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateProgressReportStatusCommand($id, $dto->status);
        $response = $this->handle($command);
        return SuccessResponse::create('progress_report_status_updated', $response->toArray());
    }
}


