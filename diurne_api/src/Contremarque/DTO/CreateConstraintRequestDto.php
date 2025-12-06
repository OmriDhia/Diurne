<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateConstraintRequestDto
{
    public function __construct(#[Assert\Type('bool')]
    public bool $transmittedPlan, #[Assert\Type('string')]
    public string $libTransmittedPlan, #[Assert\Type('bool')]
    public bool $pit, #[Assert\Type('string')]
    public string $libPit, #[Assert\Type('bool')]
    public bool $lineHeight, #[Assert\Type('string')]
    public string $libLineHeight, #[Assert\Type('bool')]
    public bool $specialThickness, #[Assert\Type('string')]
    public string $libSpecialThickness, #[Assert\Type('bool')]
    public bool $otherCarpetInTheRoom, #[Assert\Type('string')]
    public string $libOtherCarpetInTheRoom, #[Assert\Type('bool')]
    public bool $miniLength, #[Assert\Type('bool')]
    public bool $maxiLength, #[Assert\Type('bool')]
    public bool $miniWidth, #[Assert\Type('bool')]
    public bool $maxiWidth, #[Assert\Type('string')]
    public string $dstWallHeight, #[Assert\Type('string')]
    public string $dstWallDown, #[Assert\Type('string')]
    public string $dstWallLeft, #[Assert\Type('string')]
    public string $dstWallRight)
    {
    }
}
