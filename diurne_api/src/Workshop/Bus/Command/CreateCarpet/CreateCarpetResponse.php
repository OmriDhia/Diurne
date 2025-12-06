<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateCarpet;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\Carpet;

class CreateCarpetResponse implements CommandResponse
{

    public function __construct(
        private readonly Carpet $carpet
    )
    {
    }

    public function toArray(): array
    {
        return $this->carpet->toArray();
    }
}