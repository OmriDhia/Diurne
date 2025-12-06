<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateDesignerAssignment;

use DateTime;
use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\DesignerAssignment;

final readonly class CreateDesignerAssignmentResponse implements CommandResponse
{
    public function __construct(private DesignerAssignment $designerAssignment)
    {
    }

    public function toArray(): array
    {
        // Convert the DesignerAssignment entity to an array
        return [
            'id' => $this->designerAssignment->getId(),
            'designerId' => $this->designerAssignment->getDesigner()?->getId(),
            'dateFrom' => $this->designerAssignment->getDateFrom()?->format(DateTime::ISO8601),
            'dateTo' => $this->designerAssignment->getDateTo()?->format(DateTime::ISO8601),
            'inProgress' => $this->designerAssignment->isInProgress(),
            'stopped' => $this->designerAssignment->isStopped(),
            'done' => $this->designerAssignment->isDone(),
            'carpetDesignOrderId' => $this->designerAssignment->getCarpetDesignOrder()?->getId(),
        ];
    }
}
