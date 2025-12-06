<?php

namespace App\Setting\Bus\Command\Model;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateModelCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        public readonly int $id,
        #[Assert\Length(max: 50, maxMessage: 'Code cannot exceed {{ limit }} characters.')]
        public readonly ?string $code,
        public readonly ?int $numberMax
    ) {}
}
