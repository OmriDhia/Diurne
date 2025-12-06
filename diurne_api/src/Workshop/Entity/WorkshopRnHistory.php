<?php

namespace App\Workshop\Entity;

use App\Contact\Entity\Customer;
use App\Event\Entity\Event;

use App\Workshop\Repository\WorkshopRnHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class WorkshopRnHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'workshopRnHistories')]
    #[ORM\JoinColumn(name: 'event_type_id', nullable: false)]
    private ?Event $eventTypeId = null;

    #[ORM\Column(name: 'location_id', type: 'integer', nullable: true)]
    private ?int $locationId = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'workshopRnHistories')]
    #[ORM\JoinColumn(name: 'customer_id', nullable: false)]
    private ?Customer $customerId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $BeginAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $EndAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;
    #[ORM\ManyToOne(targetEntity: WorkshopOrder::class, inversedBy: 'workshopRnHistories')]
    #[ORM\JoinColumn(name: 'workshop_order_id', nullable: false)]
    private ?WorkshopOrder $workshopOrder = null;

    #[ORM\OneToOne(targetEntity: Carpet::class, inversedBy: 'workshopRnHistory')]
    #[ORM\JoinColumn(name: 'carpet_id', nullable: false)]
    private ?Carpet $carpet = null;

    public function getWorkshopOrder(): ?WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(?WorkshopOrder $workshopOrder): void
    {
        $this->workshopOrder = $workshopOrder;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventTypeId(): ?Event
    {
        return $this->eventTypeId;
    }

    public function setEventTypeId(?Event $eventTypeId): void
    {
        $this->eventTypeId = $eventTypeId;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function setLocationId(?int $locationId): void
    {
        $this->locationId = $locationId;
    }


    public function getCustomerId(): ?Customer
    {
        return $this->customerId;
    }

    public function setCustomerId(?Customer $customerId): void
    {
        $this->customerId = $customerId;
    }


    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->BeginAt;
    }

    public function setBeginAt(\DateTimeInterface $BeginAt): static
    {
        $this->BeginAt = $BeginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->EndAt;
    }

    public function setEndAt(\DateTimeInterface $EndAt): static
    {
        $this->EndAt = $EndAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'event_type_id' => $this->getEventTypeId()->getId(),
            'location_id' => $this->getLocationId(),
            'customer_id' => $this->getCustomerId()->getId(),
            'begin_at' => $this->getBeginAt()->format('Y-m-d H:i:s'),
            'end_at' => $this->getEndAt()->format('Y-m-d H:i:s'),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),

        ];
    }

    public function getCarpet(): ?Carpet
    {
        return $this->carpet;
    }

    public function setCarpet(?Carpet $carpet): void
    {
        $this->carpet = $carpet;
    }


}
