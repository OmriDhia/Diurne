<?php

namespace App\ProgressReport\Controller\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReport\DeleteProgressReport\DeleteProgressReportCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Delete a progress report.
 */
class DeleteProgressReportController extends CommandQueryController
{
    #[Route('/api/progressReport/{id}', name: 'progress_report_delete', methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteProgressReportCommand($id);
        $this->handle($command);
        return SuccessResponse::create('progress_report_deleted', null);
    }
}


