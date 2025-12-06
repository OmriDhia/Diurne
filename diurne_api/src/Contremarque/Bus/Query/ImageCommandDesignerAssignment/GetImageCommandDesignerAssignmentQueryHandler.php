<?php

namespace App\Contremarque\Bus\Query\ImageCommandDesignerAssignment;

use App\Common\Bus\Query\QueryHandler;
use App\Contremarque\Repository\ImageCommandDesignerAssignmentRepository;

class GetImageCommandDesignerAssignmentQueryHandler implements QueryHandler
{
    public function __construct(private readonly ImageCommandDesignerAssignmentRepository $imageCommandDesignerAssignmentRepository)
    {
    }


    public function __invoke(GetImageCommandDesignerAssignmentQuery $query): GetImageCommandDesignerAssignmentResponse
    {
        $icda = $this->imageCommandDesignerAssignmentRepository->findAll();
        $formattedIcda = array_map(fn($icda) => [
            'id' => $icda->getId(),
            'imageCommandId' => $icda->getImageCommand()?->getId(),
            'designerId' => $icda->getDesigner()?->getId(),
            'from' => $icda->getFromDatetime(),
            'to' => $icda->getToDatetime(),
            'in_progress' => $icda->isInProgress(),
            'stopped' => $icda->isStopped(),
            'reasonForStopping' => $icda->getReasonForStopping(),
            'done' => $icda->isDone(),
        ], $icda);
        return new GetImageCommandDesignerAssignmentResponse($formattedIcda);
    }
}