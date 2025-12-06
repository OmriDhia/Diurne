<?php

namespace App\Workshop\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'unique_category_name', columns: ['name'])]
class HistoryEventType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $name = null;
    #[ORM\OneToMany(
        targetEntity: HistoryEventTypeCategory::class,
        mappedBy: 'eventTypeId',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $historyEventTypeCategories;

    public function __construct()
    {
        $this->historyEventTypeCategories = new ArrayCollection();
    }

    public function getHistoryEventTypeCategories(): Collection
    {
        return $this->historyEventTypeCategories;
    }

    public function addHistoryEventTypeCategory(HistoryEventTypeCategory $category): static
    {
        if (!$this->historyEventTypeCategories->contains($category)) {
            $this->historyEventTypeCategories->add($category);
            $category->setEventTypeId($this);
        }
        return $this;
    }

    public function removeHistoryEventTypeCategory(HistoryEventTypeCategory $category): static
    {
        if ($this->historyEventTypeCategories->removeElement($category)) {
            if ($category->getEventTypeId() === $this) {
                $category->setEventTypeId(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }
}
