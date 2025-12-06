<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateConstraintRequestDto
{
    // Optional constructor for additional logic
    public function __construct(#[Assert\Type('bool')]
    public ?bool $transmittedPlan = null, #[Assert\Type('string')]
    #[Assert\Length(max: 128)]
    public ?string $libTransmittedPlan = null, #[Assert\Type('bool')]
    public ?bool $pit = null, #[Assert\Type('string')]
    #[Assert\Length(max: 128)]
    public ?string $libPit = null, #[Assert\Type('bool')]
    public ?bool $lineHeight = null, #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public ?string $libLineHeight = null, #[Assert\Type('bool')]
    public ?bool $specialThickness = null, #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public ?string $libSpecialThickness = null, #[Assert\Type('bool')]
    public ?bool $otherCarpetInTheRoom = null, #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public ?string $libOtherCarpetInTheRoom = null, #[Assert\Type('bool')]
    public ?bool $miniLength = null, #[Assert\Type('bool')]
    public ?bool $maxiLength = null, #[Assert\Type('bool')]
    public ?bool $miniWidth = null, #[Assert\Type('bool')]
    public ?bool $maxiWidth = null, #[Assert\Type('string')]
    public ?string $dstWallHeight = null, #[Assert\Type('string')]
    public ?string $dstWallDown = null, #[Assert\Type('string')]
    public ?string $dstWallLeft = null, #[Assert\Type('string')]
    public ?string $dstWallRight = null)
    {
    }
}
