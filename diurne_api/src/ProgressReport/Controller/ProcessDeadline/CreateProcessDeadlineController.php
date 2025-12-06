<?php

declare(strict_types=1);

namespace App\ProgressReport\Controller\ProcessDeadline;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProcessDeadline\CreateProcessDeadline\CreateProcessDeadlineCommand;
use App\ProgressReport\DTO\ProcessDeadline\CreateProcessDeadlineRequestDto;
use App\ProgressReport\Entity\ProcessDeadline;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateProcessDeadlineController extends CommandQueryController
{
    #[Route('/api/processDeadline', name: 'process_deadline_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: ProcessDeadline::class))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(#[MapRequestPayload] CreateProcessDeadlineRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateProcessDeadlineCommand(
            progressReportId: $dto->progressReportId,
            processId: $dto->processId,
            dateStart: $dto->dateStart ? new \DateTime($dto->dateStart) : null,
            dateEnd: $dto->dateEnd ? new \DateTime($dto->dateEnd) : null,
            comment: $dto->comment,
        );
        $response = $this->handle($command);
        return SuccessResponse::create('process_deadline_created', $response->toArray(), 'Created', 201);
    }
}
