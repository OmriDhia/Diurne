<?php

namespace App\Workshop\Entity;


use App\Workshop\Repository\HistoryEventCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'unique_category_name', columns: ['name'])]
class HistoryEventCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $name = null;
    #[ORM\OneToMany(
        targetEntity: HistoryEventTypeCategory::class,
        mappedBy: 'eventCategoryId',
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
            $category->setEventCategoryId($this);
        }
        return $this;
    }

    public function removeHistoryEventTypeCategory(HistoryEventTypeCategory $category): static
    {
        if ($this->historyEventTypeCategories->removeElement($category)) {
            if ($category->getEventCategoryId() === $this) {
                $category->setEventCategoryId(null);
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
