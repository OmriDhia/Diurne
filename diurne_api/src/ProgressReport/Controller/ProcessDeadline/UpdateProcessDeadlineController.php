<?php

declare(strict_types=1);

namespace App\ProgressReport\Controller\ProcessDeadline;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\ProgressReport\Bus\Command\ProcessDeadline\UpdateProcessDeadline\UpdateProcessDeadlineCommand;
use App\ProgressReport\DTO\ProcessDeadline\UpdateProcessDeadlineRequestDto;
use App\ProgressReport\Entity\ProcessDeadline;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProcessDeadlineController extends CommandQueryController
{
    #[Route('/api/processDeadline/{id}', name: 'process_deadline_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: ProcessDeadline::class))]
    #[OA\Tag(name: 'ProgressReport')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateProcessDeadlineRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'progressReport')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateProcessDeadlineCommand(
            id: $id,
            processId: $dto->processId,
            dateStart: $dto->dateStart ? new \DateTime($dto->dateStart) : null,
            dateEnd: $dto->dateEnd ? new \DateTime($dto->dateEnd) : null,
            comment: $dto->comment,
        );
        $response = $this->handle($command);
        return SuccessResponse::create('process_deadline_updated', $response->toArray());
    }
}
