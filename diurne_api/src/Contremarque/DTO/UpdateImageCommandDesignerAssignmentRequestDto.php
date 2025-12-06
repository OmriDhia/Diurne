<?php

namespace App\Contremarque\DTO;

use DateTimeInterface;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateImageCommandDesignerAssignmentRequestDto extends BaseDto
{
    #[Assert\Type('integer')]
    public ?int $imageCommandId = null;

    #[Assert\Type('integer')]
    public ?int $designerId = null;

    #[Assert\Type("\DateTimeInterface")]
    public ?DateTimeInterface $from = null;

    #[Assert\Type("\DateTimeInterface")]
    public ?DateTimeInterface $to = null;

    #[Assert\Type('boolean')]
    public ?bool $inProgress = null;

    #[Assert\Type('boolean')]
    public ?bool $stopped = null;

    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public ?string $reasonForStopping = null;

    #[Assert\Type('boolean')]
    public ?bool $done = null;
}