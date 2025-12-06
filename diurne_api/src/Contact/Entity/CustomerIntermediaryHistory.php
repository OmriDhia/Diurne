<?php

declare(strict_types=1);

namespace App\Contact\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CustomerIntermediaryHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    private ?Customer $intermediary = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'customerIntermediaryHistories')]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $dateFrom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntermediary(): ?Customer
    {
        return $this->intermediary;
    }

    public function setIntermediary(?Customer $intermediary): static
    {
        $this->intermediary = $intermediary;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getDateFrom(): ?DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(DateTimeInterface $dateFrom): static
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(?DateTimeInterface $dateTo): static
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customer?->getId(),
            'intermediary_id' => $this->intermediary?->getId(),
            'intermediaryType_id' => $this->intermediary?->getIntermediaryInformationSheet()?->getIntermediaryType()?->getId() ?? null,
            'from' => $this->dateFrom instanceof DateTimeInterface ? $this->dateFrom->format('Y-m-d H:i:s') : null,
            'to' => $this->dateTo instanceof DateTimeInterface ? $this->dateTo->format('Y-m-d H:i:s') : null,
        ];
    }
}
