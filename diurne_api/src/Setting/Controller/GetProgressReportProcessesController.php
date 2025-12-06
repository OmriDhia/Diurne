<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ProgressReportProcess\GetProgressReportProcessesQuery;
use App\Setting\Entity\ProgressReportProcess;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/progressReportProcess', name: 'progress_report_process_list', methods: ['GET'])]
class GetProgressReportProcessesController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'List', content: new Model(type: ProgressReportProcess::class))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $response = $this->ask(new GetProgressReportProcessesQuery());
        return SuccessResponse::create('progress_report_process_list', $response->toArray());
    }
}
