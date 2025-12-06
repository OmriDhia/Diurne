<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Common\Bus\Command\Command;

class CreateImageCommandCommand implements Command
{
    public function __construct(
        private readonly int     $objectId,
        private readonly string  $objectType,
        private readonly string  $commandNumber,
        private readonly ?string $commercialComment,
        private readonly ?string $advComment,
        private readonly ?string $rn,
        private readonly ?string $studioComment,
        private readonly ?int    $statusId = null
    )
    {
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getCommandNumber(): string
    {
        return $this->commandNumber;
    }

    public function getCommercialComment(): ?string
    {
        return $this->commercialComment;
    }

    public function getAdvComment(): ?string
    {
        return $this->advComment;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function getStudioComment(): ?string
    {
        return $this->studioComment;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }
}
