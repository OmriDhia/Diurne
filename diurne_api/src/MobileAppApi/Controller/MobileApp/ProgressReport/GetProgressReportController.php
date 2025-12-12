<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\ProgressReport\GetProgressReport\GetProgressReportQuery;
use App\MobileAppApi\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProgressReportController extends CommandQueryController
{
    #[Route('/api/mobile/progress-reports/{id}', name: 'get_progress_report', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get ProgressReport',
        content: new Model(type: ProgressReport::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetProgressReportQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_progress_report',
            $response->toArray(),
            'ProgressReport retrieved successfully'
        );
    }
}
