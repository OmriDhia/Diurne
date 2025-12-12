<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\ProgressReport\UpdateProgressReport\UpdateProgressReportCommand;
use App\MobileAppApi\DTO\ProgressReport\UpdateProgressReportRequestDto;
use App\MobileAppApi\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProgressReportController extends CommandQueryController
{
    #[Route('/api/mobile/progress-reports/{id}', name: 'update_progress_report', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'Update ProgressReport',
        content: new Model(type: ProgressReport::class)
    )]
    #[OA\RequestBody(
        description: 'ProgressReport data to update',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'state', type: 'string'),
                new OA\Property(property: 'isWoven', type: 'boolean'),
                new OA\Property(property: 'comment', type: 'string', nullable: true),
                new OA\Property(property: 'userId', type: 'integer', nullable: true),
            ]
        )
    )]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateProgressReportRequestDto $requestDto
    ): JsonResponse {
        $command = new UpdateProgressReportCommand(
            $id,
            $requestDto->state,
            $requestDto->isWoven,
            $requestDto->comment,
            $requestDto->userId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'update_progress_report',
            $response->toArray(),
            'ProgressReport updated successfully'
        );
    }
}
