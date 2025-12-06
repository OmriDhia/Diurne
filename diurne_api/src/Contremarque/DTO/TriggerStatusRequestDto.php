<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TriggerStatusRequestDto
{
    public function __construct(#[Assert\NotBlank(message: 'quoteDetailId must not be blank.')]
    #[Assert\Type(type: 'integer', message: 'quoteDetailId must be an integer.')]
    public int $quoteDetailId, #[Assert\Type(type: 'boolean', message: 'newStatus must be a boolean.')]
    public bool $newStatus)
    {
    }
}
