<?php

namespace App\Setting\Bus\Command\TarifTexture;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTarifTextureCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'year is required')]
        public readonly string $year
    )
    {
    }
}

