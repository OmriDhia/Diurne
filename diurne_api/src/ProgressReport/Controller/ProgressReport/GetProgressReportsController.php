<?php

namespace App\ProgressReport\Controller\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Query\ProgressReport\GetProgressReportsQuery;
use App\ProgressReport\DTO\ProgressReport\GetProgressReportsQueryDto;
use App\ProgressReport\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Retrieve progress reports list.
 */
class GetProgressReportsController extends CommandQueryController
{
    #[Route('/api/progressReport', name: 'progress_report_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new Model(type: ProgressReport::class))]
    #[OA\Parameter(name: 'provisionalCalendarId', in: 'query', description: 'Filter by provisional calendar id', schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(#[MapQueryString] GetProgressReportsQueryDto $query): JsonResponse
    {
        if (!$this->isGranted('read', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetProgressReportsQuery($query->provisionalCalendarId));
        return SuccessResponse::create('progress_report_list', $response->toArray());
    }
}


