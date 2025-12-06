<?php

namespace App\Contremarque\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProjectDi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $demande_number = null;

    #[ORM\ManyToOne(inversedBy: 'projectDis', cascade: ['persist', 'remove'])]
    private ?Contremarque $contremarque = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 2)]
    private ?string $format = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deadline = null;

    #[ORM\Column(nullable: true)]
    private ?bool $transmitted_to_studio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $transmition_date = null;
    #[ORM\ManyToOne]
    private ?UnitOfMeasurement $unit = null;
    /**
     * @var Collection<int, Attachment>
     */
    #[ORM\ManyToMany(targetEntity: Attachment::class, cascade: ['persist'])]
    private Collection $attachments;

    /**
     * @var Collection<int, CarpetDesignOrder>
     */
    #[ORM\OneToMany(targetEntity: CarpetDesignOrder::class, mappedBy: 'projectDi', cascade: ['persist'])]
    private Collection $carpetDesignOrders;

    /**
     * @var Collection<int, DiAttachment>
     */
    #[ORM\OneToMany(targetEntity: DiAttachment::class, mappedBy: 'di', cascade: ['persist'])]
    private Collection $diAttachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->carpetDesignOrders = new ArrayCollection();
        $this->diAttachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemandeNumber(): ?string
    {
        return $this->demande_number;
    }

    public function setDemandeNumber(string $demande_number): static
    {
        $this->demande_number = $demande_number;

        return $this;
    }

    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): static
    {
        $this->contremarque = $contremarque;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getDeadline(): ?DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function isTransmittedToStudio(): ?bool
    {
        return $this->transmitted_to_studio;
    }

    public function setTransmittedToStudio(?bool $transmitted_to_studio): static
    {
        $this->transmitted_to_studio = $transmitted_to_studio;

        return $this;
    }

    public function getTransmitionDate(): ?DateTimeInterface
    {
        return $this->transmition_date;
    }

    public function setTransmitionDate(?DateTimeInterface $transmition_date): static
    {
        $this->transmition_date = $transmition_date;

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachments): static
    {
        if (!$this->attachments->contains($attachments)) {
            $this->attachments->add($attachments);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachments): static
    {
        $this->attachments->removeElement($attachments);

        return $this;
    }

    public function getUnit(): ?UnitOfMeasurement
    {
        return $this->unit;
    }

    public function setUnit(?UnitOfMeasurement $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function toArray(): array
    {
        $attachmentIds = [];
        foreach ($this->getAttachments() as $attachment) {
            $attachmentIds[] = $attachment->getId(); // Assuming Attachment entity has getId() method
            // You can add more attributes from Attachment entity if needed
        }

        return [
            'project_di' => $this->getId(),
            'demande_number' => $this->getDemandeNumber(),
            'contremarque' => null !== $this->getContremarque() ? $this->getContremarque()->getId() : '',
            'createdAt' => $this->getCreatedAt(),
            'format' => $this->getFormat(),
            'deadline' => $this->getDeadline(),
            'transmitted_to_studio' => $this->isTransmittedToStudio(),
            'transmition_date' => $this->getTransmitionDate(),
            'attachments' => $attachmentIds,
            'unit' => null !== $this->getUnit() ? $this->getUnit()->toArray() : null, // Assuming UnitOfMeasurement has a toArray() method
        ];
    }

    /**
     * @return Collection<int, CarpetDesignOrder>
     */
    public function getCarpetDesignOrders(): Collection
    {
        return $this->carpetDesignOrders;
    }

    public function addCarpetDesignOrder(CarpetDesignOrder $carpetDesignOrder): static
    {
        if (!$this->carpetDesignOrders->contains($carpetDesignOrder)) {
            $this->carpetDesignOrders->add($carpetDesignOrder);
            $carpetDesignOrder->setProjectDi($this);
        }

        return $this;
    }

    public function removeCarpetDesignOrder(CarpetDesignOrder $carpetDesignOrder): static
    {
        if ($this->carpetDesignOrders->removeElement($carpetDesignOrder)) {
            // set the owning side to null (unless already changed)
            if ($carpetDesignOrder->getProjectDi() === $this) {
                $carpetDesignOrder->setProjectDi(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DiAttachment>
     */
    public function getDiAttachments(): Collection
    {
        return $this->diAttachments;
    }

    public function addDiAttachment(DiAttachment $diAttachment): static
    {
        if (!$this->diAttachments->contains($diAttachment)) {
            $this->diAttachments->add($diAttachment);
            $diAttachment->setDi($this);
        }

        return $this;
    }

    public function removeDiAttachment(DiAttachment $diAttachment): static
    {
        if ($this->diAttachments->removeElement($diAttachment)) {
            // set the owning side to null (unless already changed)
            if ($diAttachment->getDi() === $this) {
                $diAttachment->setDi(null);
            }
        }

        return $this;
    }

    public function resetUniqueFields(): void
    {
        $this->id = null;
        $this->demande_number = null;
    }

    public function __clone()
    {
        $this->resetUniqueFields();
        $this->attachments = new ArrayCollection();
        $this->carpetDesignOrders = new ArrayCollection();
        $this->diAttachments = new ArrayCollection();
    }
}
