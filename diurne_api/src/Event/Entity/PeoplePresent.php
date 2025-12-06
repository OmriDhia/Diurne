<?php

namespace App\Event\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PeoplePresent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'peoplePresents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column]
    private ?int $resourceId = null;

    #[ORM\Column(length: 255)]
    private ?string $resource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return (int|null)[]
     *
     * @psalm-return array{id: int|null, event_id: int|null}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'event_id' => $this->getEvent()->getId(),
        ];
    }

    public function getResourceId(): ?int
    {
        return $this->resourceId;
    }

    public function setResourceId(int $resourceId): static
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }
}
