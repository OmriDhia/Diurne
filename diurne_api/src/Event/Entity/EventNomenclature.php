<?php

namespace App\Event\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EventNomenclature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $subject = null;

    #[ORM\Column]
    private ?bool $is_automatic = null;

    #[ORM\Column(nullable: true)]
    private ?int $automatic_followup_delay = null;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'nomenclature')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function isIsAutomatic(): ?bool
    {
        return $this->is_automatic;
    }

    public function setIsAutomatic(bool $is_automatic): static
    {
        $this->is_automatic = $is_automatic;

        return $this;
    }

    public function getAutomaticFollowupDelay(): ?int
    {
        return $this->automatic_followup_delay;
    }

    public function setAutomaticFollowupDelay(?int $automatic_followup_delay): static
    {
        $this->automatic_followup_delay = $automatic_followup_delay;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setNomenclature($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getNomenclature() === $this) {
                $event->setNomenclature(null);
            }
        }

        return $this;
    }

    /**
     * @return (bool|int|null|string)[]
     *
     * @psalm-return array{nomenclature_id: int|null, subject: null|string, is_automatic: bool|null, automatic_followup_delay: int|null}
     */
    public function toArray(): array
    {
        return [
            'nomenclature_id' => $this->getId(),
            'subject' => $this->getSubject(),
            'is_automatic' => $this->isIsAutomatic(),
            'automatic_followup_delay' => $this->getAutomaticFollowupDelay(),
        ];
    }
}
