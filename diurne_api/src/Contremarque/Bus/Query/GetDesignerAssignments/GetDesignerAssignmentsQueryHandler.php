<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDesignerAssignments;

use DateTimeInterface;
use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\DesignerAssignmentRepository;

final readonly class GetDesignerAssignmentsQueryHandler implements QueryHandler
{
    public function __construct(
        private DesignerAssignmentRepository $designerAssignmentRepository
    ) {
    }

    public function __invoke(GetDesignerAssignmentsQuery $query)
    {
        // Fetch the designer assignments based on carpetDesignOrderId
        $designerAssignments = $this->designerAssignmentRepository->findBy(['carpetDesignOrder' => $query->carpetDesignOrderId]);

        $formattedAssignments = array_map(fn($assignment) => [
            'id' => $assignment->getId(),
            'designer' => $assignment->getDesigner()?->getId(),
            'dateFrom' => $assignment->getDateFrom()?->format(DateTimeInterface::ATOM),
            'dateTo' => $assignment->getDateTo()?->format(DateTimeInterface::ATOM),
            'inProgress' => $assignment->isInProgress(),
            'stopped' => $assignment->isStopped(),
            'done' => $assignment->isDone(),
        ], $designerAssignments);

        return new GetDesignerAssignmentsResponse($formattedAssignments);
    }
}
