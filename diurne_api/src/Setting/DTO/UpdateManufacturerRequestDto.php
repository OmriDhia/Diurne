<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateManufacturerRequestDto
{
    // Add your validation constraints here
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    public string $name;

    #[Assert\NotBlank(message: 'Company is required')]
    #[Assert\Type('string', message: 'Company must be a string')]
    public string $company;

    #[Assert\Type('string', message: 'carpetPrefix must be a string')]
    #[Assert\Length(max: 1, maxMessage: 'carpetPrefix cannot exceed {{ limit }} characters.')]
    public ?string $carpetPrefix = null;

    #[Assert\Type('string', message: 'samplePrefix must be a string')]
    #[Assert\Length(max: 1, maxMessage: 'samplePrefix cannot exceed {{ limit }} characters.')]
    public ?string $samplePrefix = null;

    #[Assert\Type('float', message: 'creditAmount must be a float')]
    public ?float $creditAmount = null;

    #[Assert\Type('float', message: 'complexityBonus must be a float')]
    public ?float $complexityBonus = null;

    #[Assert\Type('float', message: 'oversizeBonus must be a float')]
    public ?float $oversizeBonus = null;

    #[Assert\Type('float', message: 'oversizeMohaiBonus must be a float')]
    public ?float $oversizeMohaiBonus = null;

    #[Assert\Type('float', message: 'multiLevelBonus must be an float')]
    public ?float $multiLevelBonus = null;

    #[Assert\Type('int', message: 'carpetCountryID must be an integer')]
    public ?int $carpetCountryID = null;

    #[Assert\Type('int', message: 'currencyID must be an integer')]
    public ?int $currencyID = null;

    #[Assert\Type('float', message: 'specialFormBonus must be a float')]
    public ?float $specialFormBonus = null;
}
