<?php

namespace App\Workshop\Entity;

use App\Event\Entity\Event;

use App\Workshop\Repository\HistoryEventTypeCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class HistoryEventTypeCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: HistoryEventType::class, inversedBy: 'historyEventTypeCategories')]
    #[ORM\JoinColumn(name: 'event_type_id', nullable: false)]
    private ?HistoryEventType $eventTypeId = null;
    #[ORM\ManyToOne(targetEntity: HistoryEventCategory::class, inversedBy: 'historyEventTypeCategories')]
    #[ORM\JoinColumn(name: 'event_category_id', nullable: false)]
    private ?HistoryEventCategory $eventCategoryId = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventTypeId(): ?HistoryEventType
    {
        return $this->eventTypeId;
    }

    public function setEventTypeId(?HistoryEventType $eventTypeId): void
    {
        $this->eventTypeId = $eventTypeId;
    }


    public function getEventCategoryId(): ?HistoryEventCategory
    {
        return $this->eventCategoryId;
    }

    public function setEventCategoryId(?HistoryEventCategory $eventCategoryId): void
    {
        $this->eventCategoryId = $eventCategoryId;
    }


    function toArray(): array
    {
        return [
            'id' => $this->id,
            'event_type_id' => $this->eventTypeId->getId(),
            'event_type__name' => $this->eventTypeId->getName(),
            'event_category_id' => $this->eventCategoryId->getId(),
            'event_category_name' => $this->eventCategoryId->getName(),
        ];
    }


}
