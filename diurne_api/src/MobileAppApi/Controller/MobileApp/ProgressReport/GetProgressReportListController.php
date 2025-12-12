<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\ProgressReport\GetProgressReportList\GetProgressReportListQuery;
use App\MobileAppApi\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProgressReportListController extends CommandQueryController
{
    #[Route('/api/mobile/progress-reports', name: 'get_progress_report_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get list of ProgressReports',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ProgressReport::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetProgressReportListQuery();
        $response = $this->ask($query);

        $data = array_map(fn(ProgressReport $pr) => $pr->toArray(), $response);

        return SuccessResponse::create(
            'get_progress_report_list',
            $data,
            'ProgressReport list retrieved successfully'
        );
    }
}
