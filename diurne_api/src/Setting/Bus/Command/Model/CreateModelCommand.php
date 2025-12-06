<?php

namespace App\Setting\Bus\Command\Model;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class CreateModelCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'Code cannot be empty.')]
        #[Assert\Length(max: 50, maxMessage: 'Code cannot exceed {{ limit }} characters.')]
        public readonly string $code,
        #[Assert\NotBlank(message: 'Number max cannot be empty.')]
        public readonly int $numberMax
    ) {}
}
