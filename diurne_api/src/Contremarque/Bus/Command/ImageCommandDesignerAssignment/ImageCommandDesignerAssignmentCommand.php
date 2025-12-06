<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

class ImageCommandDesignerAssignmentCommand implements Command
{
    public function __construct(private readonly int $imageCommandId, private readonly int $designerId, private readonly ?DateTimeImmutable $from, private readonly ?DateTimeImmutable $to, private readonly ?bool $in_progress, private readonly ?bool $stopped, private readonly ?string $reasonForStopping, private readonly ?bool $done)
    {
    }

    public function getImageCommandId(): int
    {
        return $this->imageCommandId;
    }

    public function getDesignerId(): int
    {
        return $this->designerId;
    }

    public function getFrom(): DateTimeImmutable|null
    {
        return $this->from;
    }

    public function getTo(): DateTimeImmutable|null
    {
        return $this->to;
    }

    public function getInProgress(): ?bool
    {
        return $this->in_progress;
    }

    public function getStopped(): ?bool
    {
        return $this->stopped;
    }

    public function getReasonForStopping(): ?string
    {
        return $this->reasonForStopping;
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }
}