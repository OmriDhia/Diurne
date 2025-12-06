<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateCustomerInstruction;

use App\Common\Bus\Command\Command;

class UpdateCustomerInstructionCommand implements Command
{
    public function __construct(
        private readonly int     $carpetDesignOrderId,
        private readonly string  $objectType,
        private readonly int     $customerInstructionId,
        private readonly ?string $orderNumber,
        private readonly ?string $transmissionAdvice,
        private readonly ?string $customerComment,
        private readonly ?string $customerValidationDate,
        private readonly ?bool   $hasConstraints,
        private readonly ?bool   $hasValidateSample,
        private readonly ?bool   $hasFinitionInstruction,
        private readonly ?int    $validatedSampleId,
        private readonly ?int    $finitionInstructionId,
        private readonly ?int    $constraintInstructionId
    )
    {
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

    public function getCustomerInstructionId(): int
    {
        return $this->customerInstructionId;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function getTransmissionAdvice(): ?string
    {
        return $this->transmissionAdvice;
    }

    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }

    public function getCustomerValidationDate(): ?string
    {
        return $this->customerValidationDate;
    }

    public function hasConstraints(): ?bool
    {
        return $this->hasConstraints;
    }

    public function hasValidateSample(): ?bool
    {
        return $this->hasValidateSample;
    }

    public function hasFinitionInstruction(): ?bool
    {
        return $this->hasFinitionInstruction;
    }

    public function getValidatedSampleId(): ?int
    {
        return $this->validatedSampleId;
    }

    public function getFinitionInstructionId(): ?int
    {
        return $this->finitionInstructionId;
    }

    public function getConstraintInstructionId(): ?int
    {
        return $this->constraintInstructionId;
    }
}
