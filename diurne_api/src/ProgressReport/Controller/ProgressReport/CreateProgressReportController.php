<?php

namespace App\ProgressReport\Controller\ProgressReport;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProgressReport\CreateProgressReport\CreateProgressReportCommand;
use App\ProgressReport\DTO\ProgressReport\CreateProgressReportRequestDto;
use App\ProgressReport\Entity\ProgressReport;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller used to create a progress report.
 */
class CreateProgressReportController extends CommandQueryController
{
    #[Route('/api/progressReport', name: 'progress_report_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: ProgressReport::class))]
    #[OA\RequestBody(
        description: 'Progress report data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'authorId', type: 'integer', example: 1),
                new OA\Property(property: 'datePr', type: 'string', example: '2024-01-01'),
                new OA\Property(property: 'comment', type: 'string', example: 'comment'),
                new OA\Property(property: 'dateEvent', type: 'string', example: '2024-01-02'),
                new OA\Property(property: 'dateWorkshop', type: 'string', example: '2024-01-03'),
                new OA\Property(property: 'tissage', type: 'string', example: 'tight'),
                new OA\Property(property: 'statusId', type: 'integer', example: 1),
                new OA\Property(property: 'provisionalCalendarId', type: 'integer', example: 1)
            ]
        )
    )]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(#[MapRequestPayload] CreateProgressReportRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateProgressReportCommand(
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
        return SuccessResponse::create('progress_report_created', $response->toArray(), 'Created', 201);
    }
}


