<?php

namespace App\Contremarque\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Dimension
{
    public function __construct(#[ORM\Column(type: 'decimal', precision: 20, scale: 6)]
    #[Assert\NotBlank(message: 'Width cannot be empty')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Width must be greater than or equal to 0')]
    private readonly string $width, #[ORM\Column(type: 'decimal', precision: 20, scale: 6)]
    #[Assert\NotBlank(message: 'Height cannot be empty')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Height must be greater than or equal to 0')]
    private readonly string $height)
    {
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    // Optional: Add a method to check equality between two Dimension objects
    public function equals(Dimension $other): bool
    {
        return $this->width === $other->width && $this->height === $other->height;
    }

    // Optional: Add a toArray method for serialization
    public function toArray(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
