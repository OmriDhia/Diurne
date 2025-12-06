<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteTransportTypeCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        public readonly int $id
    ) {}
}
