<?php

declare(strict_types=1);

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetAllTarifRequestDto
{
    public function __construct(#[Assert\Type('integer')]
    #[Assert\Positive]
    #[Assert\Optional]
    public ?int $discountRuleId = null)
    {
    }
}
