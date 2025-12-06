<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateCustomerInstruction;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CustomerInstruction;

final readonly class UpdateCustomerInstructionResponse implements CommandResponse
{
    public function __construct(private CustomerInstruction $customerInstruction)
    {
    }

    public function toArray(): array
    {
        return $this->customerInstruction->toArray();
    }
}
