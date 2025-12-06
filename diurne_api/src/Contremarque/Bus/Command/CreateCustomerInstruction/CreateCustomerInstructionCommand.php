<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateCustomerInstruction;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class CreateCustomerInstructionCommand implements Command
{
    public function __construct(private readonly int $carpetDesignOrderId, private readonly string $objectType, private readonly string $orderNumber, private readonly ?DateTimeInterface $transmiAdv, private readonly string $customerInsValidationsDate, private readonly ?string $customerComment = null)
    {
    }

    public function getCustomerInsValidationsDate(): string
    {
        return $this->customerInsValidationsDate;
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getTransmiAdv(): ?DateTimeInterface
    {
        return $this->transmiAdv;
    }

    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }
}
