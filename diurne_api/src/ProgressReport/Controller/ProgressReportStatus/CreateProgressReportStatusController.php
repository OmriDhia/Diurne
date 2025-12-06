<?php

namespace App\ProgressReport\Controller\ProgressReportStatus;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReportStatus\CreateProgressReportStatus\CreateProgressReportStatusCommand;
use App\ProgressReport\DTO\ProgressReportStatus\CreateProgressReportStatusRequestDto;
use App\ProgressReport\Entity\ProgressReportStatus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Create a progress report status.
 */
class CreateProgressReportStatusController extends CommandQueryController
{
    #[Route('/api/progressReportStatus', name: 'progress_report_status_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: ProgressReportStatus::class))]
    #[OA\RequestBody(
        description: 'Progress report status data',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'status', type: 'string', example: 'in progress')])
    )]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(#[MapRequestPayload] CreateProgressReportStatusRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateProgressReportStatusCommand($dto->status);
        $response = $this->handle($command);
        return SuccessResponse::create('progress_report_status_created', $response->toArray(), 'Created', 201);
    }
}


