<?php

namespace App\Contremarque\DTO;

use DateTimeInterface;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateImageCommandDesignerAssignmentRequestDto extends BaseDto
{
    #[Assert\Type('integer')]
    #[Assert\NotNull()]
    public ?int $imageCommandId = null;

    #[Assert\Type('integer')]
    #[Assert\NotNull()]
    public ?int $designerId = null;

    #[Assert\Type("\DateTimeInterface")]
    #[Assert\NotNull()]
    public ?DateTimeInterface $from = null;

    #[Assert\Type("\DateTimeInterface")]
    #[Assert\NotNull()]
    public ?DateTimeInterface $to = null;

    #[Assert\Type('boolean')]
    public ?bool $inProgress = false;

    #[Assert\Type('boolean')]
    public ?bool $stopped = false;

    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public ?string $reasonForStopping = null;

    #[Assert\Type('boolean')]
    public ?bool $done = false;
}