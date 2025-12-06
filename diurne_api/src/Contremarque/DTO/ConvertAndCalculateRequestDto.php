<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ConvertAndCalculateRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'The larg value is required.')]
        #[Assert\Type(type: 'float', message: 'The larg value must be a float.')]
        public float $largCm,
        #[Assert\NotBlank(message: 'The lng value is required.')]
        #[Assert\Type(type: 'float', message: 'The lng value must be a float.')]
        public float $lngCm,
        #[Assert\NotBlank(message: 'The larg value is required.')]
        #[Assert\Type(type: 'float', message: 'The larg value must be a float.')]
        public float $largFeet,
        #[Assert\NotBlank(message: 'The lng value is required.')]
        #[Assert\Type(type: 'float', message: 'The lng value must be a float.')]
        public float $lngFeet,
        #[Assert\NotBlank(message: 'The larg value is required.')]
        #[Assert\Type(type: 'float', message: 'The larg value must be a float.')]
        public float $largInches,
        #[Assert\NotBlank(message: 'The lng value is required.')]
        #[Assert\Type(type: 'float', message: 'The lng value must be a float.')]
        public float $lngInches,
        #[Assert\NotBlank(message: 'The InputUnit value is required.')]
        #[Assert\Type(type: 'string', message: 'The InputUnit value must be a string.')]
        public string $InputUnit,
        #[Assert\Type(type: 'integer', message: 'The lng value must be a integer.')]
        public ?int $quoteDetailId,
        public ?float $totalPriceHt,
        public ?int $currencyId,
    ) {
    }
}
