<?php

namespace App\Event\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use App\Contact\Entity\Customer;
use App\Contremarque\Entity\Contremarque;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?EventNomenclature $nomenclature = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $next_reminder_deadline = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reminder_disabled = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(nullable: true)]
    private ?array $people_present = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $event_date = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Contremarque $contremarque = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: PeoplePresent::class)]
    private Collection $peoplePresents;

    public function __construct()
    {
        $this->peoplePresents = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomenclature(): ?EventNomenclature
    {
        return $this->nomenclature;
    }

    public function setNomenclature(?EventNomenclature $nomenclature): static
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    public function getNextReminderDeadline(): ?DateTimeInterface
    {
        return $this->next_reminder_deadline;
    }

    public function setNextReminderDeadline(?DateTimeInterface $next_reminder_deadline): static
    {
        $this->next_reminder_deadline = $next_reminder_deadline;

        return $this;
    }

    public function isReminderDisabled(): ?bool
    {
        return $this->reminder_disabled;
    }

    public function setReminderDisabled(?bool $reminder_disabled): static
    {
        $this->reminder_disabled = $reminder_disabled;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPeoplePresent(): ?array
    {
        return $this->people_present;
    }

    public function setPeoplePresent(?array $people_present): static
    {
        $this->people_present = $people_present;

        return $this;
    }

    public function getEventDate(): ?DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(DateTimeInterface $event_date): static
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    /**
     * @return (DateTimeInterface|array|bool|int|mixed|null|string)[]
     *
     * @psalm-return array{event_id: int|null, nomenclature: array<never, never>|mixed, next_reminder_deadline: DateTimeInterface|null, reminder_disabled: bool|null, commentaire: null|string, people_present: ''|array|null, event_date: DateTimeInterface|null}
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getId(),
            'nomenclature' => !empty($this->getNomenclature()) ? $this->getNomenclature()->toArray() : [],
            'next_reminder_deadline' => $this->getNextReminderDeadline(),
            'reminder_disabled' => $this->isReminderDisabled(),
            'commentaire' => $this->getCommentaire(),
            'people_present' => !empty($this->getPeoplePresent()) ? $this->getPeoplePresent() : '',
            'event_date' => $this->getEventDate(),
        ];
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

    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): static
    {
        $this->contremarque = $contremarque;

        return $this;
    }
    public function getPeoplePresents(): Collection
    {
        return $this->peoplePresents;
    }

    public function addPeoplePresent(PeoplePresent $peoplePresent): self
    {
        if (!$this->peoplePresents->contains($peoplePresent)) {
            $this->peoplePresents->add($peoplePresent);
            $peoplePresent->setEvent($this);
        }
        return $this;
    }

    public function removePeoplePresent(PeoplePresent $peoplePresent): self
    {
        if ($this->peoplePresents->removeElement($peoplePresent)) {
            if ($peoplePresent->getEvent() === $this) {
                $peoplePresent->setEvent(null);
            }
        }
        return $this;
    }
}
