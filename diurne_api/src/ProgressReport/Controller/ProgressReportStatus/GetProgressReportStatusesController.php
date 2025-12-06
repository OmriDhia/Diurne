<?php

namespace App\ProgressReport\Controller\ProgressReportStatus;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Query\ProgressReportStatus\GetProgressReportStatusesQuery;
use App\ProgressReport\Entity\ProgressReportStatus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * List all progress report statuses.
 */
class GetProgressReportStatusesController extends CommandQueryController
{
    #[Route('/api/progressReportStatus', name: 'progress_report_status_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new Model(type: ProgressReportStatus::class))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetProgressReportStatusesQuery());
        return SuccessResponse::create('progress_report_status_list', $response->toArray());
    }
}


