<?php

namespace App\ProgressReport\Controller\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReport\UpdateProgressReport\UpdateProgressReportCommand;
use App\ProgressReport\DTO\ProgressReport\UpdateProgressReportRequestDto;
use App\ProgressReport\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Update an existing progress report.
 */
class UpdateProgressReportController extends CommandQueryController
{
    #[Route('/api/progressReport/{id}', name: 'progress_report_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: ProgressReport::class))]
    #[OA\RequestBody(
        description: 'Update progress report data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'authorId', type: 'integer', example: 1),
                new OA\Property(property: 'datePr', type: 'string', example: '2024-01-01'),
                new OA\Property(property: 'comment', type: 'string', example: 'comment updated'),
                new OA\Property(property: 'dateEvent', type: 'string', example: '2024-01-02'),
                new OA\Property(property: 'dateWorkshop', type: 'string', example: '2024-01-03'),
                new OA\Property(property: 'tissage', type: 'string', example: 'tight'),
                new OA\Property(property: 'statusId', type: 'integer', example: 2),
                new OA\Property(property: 'provisionalCalendarId', type: 'integer', example: 1)
            ]
        )
    )]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateProgressReportRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }
        $command = new UpdateProgressReportCommand(
            id: $id,
            authorId: $dto->authorId,
            datePr: $dto->datePr ? new \DateTime($dto->datePr) : null,
            comment: $dto->comment,
            dateEvent: $dto->dateEvent ? new \DateTime($dto->dateEvent) : null,
            dateWorkshop: $dto->dateWorkshop ? new \DateTime($dto->dateWorkshop) : null,
            tissage: $dto->tissage,
            statusId: $dto->statusId,
            provisionalCalendarId: $dto->provisionalCalendarId,
        );
        $response = $this->handle($command);
        return SuccessResponse::create('progress_report_updated', $response->toArray());
    }
}


