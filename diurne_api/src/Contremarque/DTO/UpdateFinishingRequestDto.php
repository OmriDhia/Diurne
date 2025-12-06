<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateFinishingRequestDto
{
    #[Assert\Length(max: 255)]
    public ?string $fabricColor = null;

    public ?bool $fringe = null;

    public ?bool $withoutBanking = null;

    public ?bool $noBinding = null;

    public ?bool $mzCarved = null;

    #[Assert\Length(max: 255)]
    public ?string $otherCarvedSignature = null;

    #[Assert\Regex(pattern: "/^\d+(\.\d+)?$/")]
    public ?string $standardVelvetHeight = null;

    #[Assert\Regex(pattern: "/^\d+(\.\d+)?$/")]
    public ?string $specialVelvetHeight = null;
}
