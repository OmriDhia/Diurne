<?php

namespace App\Contremarque\Bus\Command\ImageCommand;

use App\Common\Bus\Command\Command;

class UpdateImageCommandCommand implements Command
{
    public function __construct(
        private int              $id,
        private readonly ?string $commandNumber,
        private readonly ?string $commercialComment,
        private readonly ?string $advComment,
        private readonly ?string $rn,
        private ?string          $studioComment,
        private readonly ?int    $statusId = null
    )
    {
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getStudioComment(): ?string
    {
        return $this->studioComment;
    }

    public function setStudioComment(?string $studioComment): void
    {
        $this->studioComment = $studioComment;
    }

    public function getCommandNumber(): string|null
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

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

}
