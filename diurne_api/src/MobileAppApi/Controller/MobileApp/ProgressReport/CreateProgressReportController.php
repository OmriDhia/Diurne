<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\ProgressReport\CreateProgressReport\CreateProgressReportCommand;
use App\MobileAppApi\DTO\ProgressReport\CreateProgressReportRequestDto;
use App\MobileAppApi\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateProgressReportController extends CommandQueryController
{
    #[Route('/api/mobile/progress-reports', name: 'create_progress_report', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'ProgressReport creation',
        content: new Model(type: ProgressReport::class)
    )]
    #[OA\RequestBody(
        description: 'ProgressReport data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'rnId', type: 'integer'),
                new OA\Property(property: 'state', type: 'string'),
                new OA\Property(property: 'isWoven', type: 'boolean'),
                new OA\Property(property: 'comment', type: 'string', nullable: true),
                new OA\Property(property: 'userId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateProgressReportRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateProgressReportCommand(
            $requestDto->rnId,
            $requestDto->state,
            $requestDto->isWoven,
            $requestDto->comment,
            $requestDto->userId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_progress_report',
            $response->toArray(),
            'ProgressReport created successfully'
        );
    }
}
