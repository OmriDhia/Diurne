<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateFinishingRequestDto
{
    #[Assert\Type('string', message: 'Fabric color must be a string.')]
    public ?string $fabricColor = null;

    #[Assert\Type('boolean', message: 'Fringe must be a boolean value.')]
    public ?bool $fringe = null;

    #[Assert\Type('boolean', message: 'Without banking must be a boolean value.')]
    public ?bool $withoutBanking = null;

    #[Assert\Type('boolean', message: 'No binding must be a boolean value.')]
    public ?bool $noBinding = null;

    #[Assert\Type('boolean', message: 'MZ Carved must be a boolean value.')]
    public ?bool $mzCarved = null;

    #[Assert\Type('string', message: 'Other carved signature must be a string.')]
    public ?string $otherCarvedSignature = null;

    #[Assert\Type('string', message: 'Standard velvet height must be a string.')]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d+)?$/',
        message: 'Standard velvet height must be a valid decimal number.'
    )]
    public ?string $standardVelvetHeight = null;

    #[Assert\Type('string', message: 'Special velvet height must be a string.')]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d+)?$/',
        message: 'Special velvet height must be a valid decimal number.'
    )]
    public ?string $specialVelvetHeight = null;
}
