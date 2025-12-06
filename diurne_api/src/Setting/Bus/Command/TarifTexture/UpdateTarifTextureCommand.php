<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifTexture;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTarifTextureCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        public readonly int    $id,
        #[Assert\NotBlank(message: 'year is required')]
        public readonly string $year
    )
    {
    }
}

