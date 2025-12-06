<?php

declare(strict_types=1);

namespace App\Setting\DTO;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTarifGroupRequestDto implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'Year cannot be empty.')]
        #[Assert\Length(max: 20, maxMessage: 'Year cannot exceed {{ limit }} characters.')]
        #[Assert\Type('string', message: 'year must be a string')]
        public string $year
    ) {
    }
}
