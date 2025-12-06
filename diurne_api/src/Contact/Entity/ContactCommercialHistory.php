<?php

namespace App\Contact\Entity;

use DateTimeInterface;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'idx_cch_customer_status_id', columns: ['customer_id', 'status_id', 'id'])]
class ContactCommercialHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'contactCommercialHistories')]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $fromDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $toDate = null;

    #[ORM\ManyToOne]
    private ?User $commercial = null;

    #[ORM\ManyToOne]
    private ?AttributionStatus $status = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFromDate(): ?DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(DateTimeInterface $fromDate): static
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): static
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * @return (int|null|string)[]
     *
     * @psalm-return array{customer_id: int|null, commercial_id: int|null, firstname: null|string, lastname: null|string, status: null|string, from: string, to: string}
     */
    public function toArray(): array
    {
        return [
            'customer_id' => $this->getCustomer()->getId(),
            'commercial_id' => $this->getCommercial()->getId(),
            'firstname' => $this->getCommercial()->getFirstname(),
            'lastname' => $this->getCommercial()->getLastname(),
            'status' => $this->getStatus()->getName(),
            'from' => $this->getFromDate()->format('Y-m-d H:i:s'),
            'to' => !empty($this->getToDate()) ? $this->getToDate()->format('Y-m-d H:i:s') : '',
        ];
    }

    public function getStatus(): ?AttributionStatus
    {
        return $this->status;
    }

    public function setStatus(?AttributionStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
