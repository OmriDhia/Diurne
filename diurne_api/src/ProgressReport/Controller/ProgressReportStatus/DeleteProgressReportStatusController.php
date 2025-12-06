<?php

namespace App\ProgressReport\Controller\ProgressReportStatus;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReportStatus\DeleteProgressReportStatus\DeleteProgressReportStatusCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Delete a progress report status.
 */
class DeleteProgressReportStatusController extends CommandQueryController
{
    #[Route('/api/progressReportStatus/{id}', name: 'progress_report_status_delete', methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $this->handle(new DeleteProgressReportStatusCommand($id));
        return SuccessResponse::create('progress_report_status_deleted', null);
    }
}


