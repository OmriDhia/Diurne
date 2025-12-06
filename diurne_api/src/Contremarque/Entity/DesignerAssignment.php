<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use DateTime;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'idx_da_cdo_date', columns: ['carpet_design_order_id', 'date_from', 'date_to'], options: ['order' => 'DESC'])]
class DesignerAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $designer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $date_from = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $date_to = null;

    #[ORM\Column]
    private ?bool $in_progress = null;

    #[ORM\Column]
    private ?bool $stopped = null;

    #[ORM\Column]
    private ?bool $done = null;

    #[ORM\ManyToOne(targetEntity: CarpetDesignOrder::class, inversedBy: 'designers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetDesignOrder $carpetDesignOrder = null;

    public function setInProgress(bool $in_progress): static
    {
        $this->in_progress = $in_progress;

        return $this;
    }

    public function setStopped(bool $stopped): static
    {
        $this->stopped = $stopped;

        return $this;
    }

    public function setDone(bool $done): static
    {
        $this->done = $done;

        return $this;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'designer' => $this->getDesigner() ? $this->getDesigner()->getId() : null,
            'date_from' => $this->getDateFrom()?->format('Y-m-d H:i:s'),
            'date_to' => $this->getDateTo()?->format('Y-m-d H:i:s'),
            'in_progress' => $this->isInProgress(),
            'stopped' => $this->isStopped(),
            'done' => $this->isDone(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesigner(): ?User
    {
        return $this->designer;
    }

    public function setDesigner(?User $designer): static
    {
        $this->designer = $designer;

        return $this;
    }

    public function getDateFrom(): ?DateTimeInterface
    {
        return $this->date_from;
    }

    public function setDateFrom(DateTimeInterface $date_from): static
    {
        $this->date_from = $date_from;

        return $this;
    }

    public function getDateTo(): ?DateTimeInterface
    {
        return $this->date_to;
    }

    public function setDateTo(DateTimeInterface|string|null $date_to): static
    {
        // If the input is an empty string or null, set the field to null
        if (empty($date_to)) {
            $this->date_to = null;
        } elseif (is_string($date_to)) {
            $this->date_to = new DateTime($date_to);
        } else {
            $this->date_to = $date_to;
        }

        return $this;
    }

    public function isInProgress(): ?bool
    {
        return $this->in_progress;
    }

    public function isStopped(): ?bool
    {
        return $this->stopped;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }
}
