<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ImageCommand\ImageCommandDesignerAssignment;

class ImageCommandDesignerAssignmentResponse implements CommandResponse
{
    public function __construct(private readonly ImageCommandDesignerAssignment $imageCommandDesignerAssignment)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->imageCommandDesignerAssignment->getId(),
            'imageCommand' => $this->imageCommandDesignerAssignment->getImageCommand()?->getId(),
            'designer' => $this->imageCommandDesignerAssignment->getDesigner()->getId(),
            'from' => $this->imageCommandDesignerAssignment->getFromDatetime()->format('Y-m-d H:i:s'),
            'to' => $this->imageCommandDesignerAssignment->getToDatetime()->format('Y-m-d H:i:s'),
            'in_progress' => $this->imageCommandDesignerAssignment->isInProgress(),
            'stopped' => $this->imageCommandDesignerAssignment->isStopped(),
            'reason_for_stopping' => $this->imageCommandDesignerAssignment->getReasonForStopping(),
            'done' => $this->imageCommandDesignerAssignment->isDone(),
        ];
    }
}