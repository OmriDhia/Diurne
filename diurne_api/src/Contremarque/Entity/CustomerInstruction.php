<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use App\Contremarque\Entity\CarpetDesignOrder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CustomerInstruction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'customerInstruction', cascade: ['remove'])]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $orderNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $transmi_adv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $customerValidationDate = null;

    #[ORM\Column]
    private ?bool $hasCustomerConstraints = null;

    #[ORM\Column]
    private ?bool $hasValidateSample = null;

    #[ORM\Column]
    private ?bool $hasFinitionInstruction = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customerComment = null;

    #[ORM\OneToOne(targetEntity: Finishing::class, inversedBy: 'customerInstruction')]
    #[ORM\JoinColumn(name: 'finition_instruction_id', referencedColumnName: 'id', nullable: true)]
    private ?Finishing $finitionInstruction = null;

    #[ORM\OneToOne(targetEntity: ValidatedSample::class, inversedBy: 'customerInstruction')]
    #[ORM\JoinColumn(name: 'validated_sample_id', referencedColumnName: 'id', nullable: true)]
    private ?ValidatedSample $validatedSample = null;

    #[ORM\OneToOne(inversedBy: 'customerInstruction', cascade: ['persist', 'remove'])]
    private ?CustomerConstraint $customerConstraint = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarpetDesignOrder(): ?CarpetDesignOrder
    {
        return $this->carpetDesignOrder;
    }

    public function setCarpetDesignOrder(?CarpetDesignOrder $carpetDesignOrder): static
    {
        $this->carpetDesignOrder = $carpetDesignOrder;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?string $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getTransmiAdv(): ?DateTimeInterface
    {
        return $this->transmi_adv;
    }

    public function setTransmiAdv(?DateTimeInterface $transmi_adv): static
    {
        $this->transmi_adv = $transmi_adv;

        return $this;
    }

    public function getCustomerValidationDate(): ?DateTimeInterface
    {
        return $this->customerValidationDate;
    }

    public function setCustomerValidationDate(?DateTimeInterface $customerValidationDate): static
    {
        $this->customerValidationDate = $customerValidationDate;

        return $this;
    }

    public function hasCustomerConstraints(): ?bool
    {
        return $this->hasCustomerConstraints;
    }

    public function setHasCustomerConstraints(bool $hasCustomerConstraints): static
    {
        $this->hasCustomerConstraints = $hasCustomerConstraints;

        return $this;
    }

    public function hasValidateSample(): ?bool
    {
        return $this->hasValidateSample;
    }

    public function setHasValidateSample(bool $hasValidateSample): static
    {
        $this->hasValidateSample = $hasValidateSample;

        return $this;
    }

    public function hasFinitionInstruction(): ?bool
    {
        return $this->hasFinitionInstruction;
    }

    public function setHasFinitionInstruction(bool $hasFinitionInstruction): static
    {
        $this->hasFinitionInstruction = $hasFinitionInstruction;

        return $this;
    }

    public function getCustomerComment(): ?string
    {
        return $this->customerComment;
    }

    public function setCustomerComment(?string $customerComment): static
    {
        $this->customerComment = $customerComment;

        return $this;
    }

    public function getValidatedSample(): ?ValidatedSample
    {
        return $this->validatedSample;
    }

    public function setValidatedSample(?ValidatedSample $validatedSample): static
    {
        $this->validatedSample = $validatedSample;
        $this->hasValidateSample = null !== $validatedSample;

        return $this;
    }

    public function getFinitionInstruction(): ?Finishing
    {
        return $this->finitionInstruction;
    }

    public function setFinitionInstruction(?Finishing $finitionInstruction): static
    {
        $this->finitionInstruction = $finitionInstruction;
        $this->hasFinitionInstruction = null !== $finitionInstruction;

        return $this;
    }

    public function getCustomerConstraint(): ?CustomerConstraint
    {
        return $this->customerConstraint;
    }

    public function setCustomerConstraint(?CustomerConstraint $customerConstraint): static
    {
        $this->customerConstraint = $customerConstraint;
        $this->hasCustomerConstraints = null !== $customerConstraint;

        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'carpetDesignOrderId' => $this->getCarpetDesignOrder()?->getId(),
            'orderNumber' => $this->getOrderNumber(),
            'transmi_adv' => $this->getTransmiAdv()?->format('Y-m-d H:i:s'),
            'customerValidationDate' => $this->getCustomerValidationDate()?->format('Y-m-d'),
            'hasCustomerConstraints' => $this->hasCustomerConstraints(),
            'hasValidateSample' => $this->hasValidateSample(),
            'hasFinitionInstruction' => $this->hasFinitionInstruction(),
            'customerComment' => $this->getCustomerComment(),
            'validatedSample' => $this->getValidatedSample()?->toArray(),
            'finitionInstruction' => $this->getFinitionInstruction()?->toArray(),
            'customerConstraint' => $this->getCustomerConstraint()?->toArray(),
        ];
    }
}
