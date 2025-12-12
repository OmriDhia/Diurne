<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\ProgressReport\DeleteProgressReport\DeleteProgressReportCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class DeleteProgressReportController extends CommandQueryController
{
    #[Route('/api/mobile/progress-reports/{id}', name: 'delete_progress_report', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete ProgressReport'
    )]
    public function __invoke(int $id): JsonResponse
    {
        $command = new DeleteProgressReportCommand($id);
        $this->handle($command);

        return SuccessResponse::create(
            'delete_progress_report',
            [],
            'ProgressReport deleted successfully'
        );
    }
}
