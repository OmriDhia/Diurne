<?php
declare(strict_types=1);

namespace App\ProgressReport\Entity;


use App\Workshop\Entity\WorkshopOrder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\ProgressReport\Entity\ProgressReport;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProvisionalCalendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private WorkshopOrder $workshopOrder;

    #[ORM\Column(type: 'integer')]
    private int $deadlinPreparation;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateEndPreparation;

    #[ORM\Column(type: 'integer')]
    private int $deadlinWeave;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateEndWeave;

    #[ORM\Column(type: 'integer')]
    private int $deadlinFinition;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateEndFinition;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $eventPreparation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $stopPreparation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $eventWeave = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $stopWeave = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $eventFinition = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $stopFinition = null;

    /**
     * @var Collection<int, ProgressReport>
     */
    #[ORM\OneToMany(mappedBy: 'provisionalCalendar', targetEntity: ProgressReport::class)]
    private Collection $progressReports;

    public function __construct()
    {
        $this->progressReports = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWorkshopOrder(): WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(WorkshopOrder $workshopOrder): void
    {
        $this->workshopOrder = $workshopOrder;
    }

    public function getDeadlinPreparation(): int
    {
        return $this->deadlinPreparation;
    }

    public function setDeadlinPreparation(int $deadlinPreparation): void
    {
        $this->deadlinPreparation = $deadlinPreparation;
    }

    public function getDateEndPreparation(): \DateTimeInterface
    {
        return $this->dateEndPreparation;
    }

    public function setDateEndPreparation(\DateTimeInterface $dateEndPreparation): void
    {
        $this->dateEndPreparation = $dateEndPreparation;
    }

    public function getDeadlinWeave(): int
    {
        return $this->deadlinWeave;
    }

    public function setDeadlinWeave(int $deadlinWeave): void
    {
        $this->deadlinWeave = $deadlinWeave;
    }

    public function getDateEndWeave(): \DateTimeInterface
    {
        return $this->dateEndWeave;
    }

    public function setDateEndWeave(\DateTimeInterface $dateEndWeave): void
    {
        $this->dateEndWeave = $dateEndWeave;
    }

    public function getDeadlinFinition(): int
    {
        return $this->deadlinFinition;
    }

    public function setDeadlinFinition(int $deadlinFinition): void
    {
        $this->deadlinFinition = $deadlinFinition;
    }

    public function getDateEndFinition(): \DateTimeInterface
    {
        return $this->dateEndFinition;
    }

    public function setDateEndFinition(\DateTimeInterface $dateEndFinition): void
    {
        $this->dateEndFinition = $dateEndFinition;
    }

    public function getEventPreparation(): ?string
    {
        return $this->eventPreparation;
    }

    public function setEventPreparation(?string $eventPreparation): void
    {
        $this->eventPreparation = $eventPreparation;
    }

    public function getStopPreparation(): ?string
    {
        return $this->stopPreparation;
    }

    public function setStopPreparation(?string $stopPreparation): void
    {
        $this->stopPreparation = $stopPreparation;
    }

    public function getEventWeave(): ?string
    {
        return $this->eventWeave;
    }

    public function setEventWeave(?string $eventWeave): void
    {
        $this->eventWeave = $eventWeave;
    }

    public function getStopWeave(): ?string
    {
        return $this->stopWeave;
    }

    public function setStopWeave(?string $stopWeave): void
    {
        $this->stopWeave = $stopWeave;
    }

    public function getEventFinition(): ?string
    {
        return $this->eventFinition;
    }

    public function setEventFinition(?string $eventFinition): void
    {
        $this->eventFinition = $eventFinition;
    }

    public function getStopFinition(): ?string
    {
        return $this->stopFinition;
    }

    public function setStopFinition(?string $stopFinition): void
    {
        $this->stopFinition = $stopFinition;
    }

    /**
     * @return Collection<int, ProgressReport>
     */
    public function getProgressReports(): Collection
    {
        return $this->progressReports;
    }

    public function addProgressReport(ProgressReport $progressReport): static
    {
        if (!$this->progressReports->contains($progressReport)) {
            $this->progressReports->add($progressReport);
            $progressReport->setProvisionalCalendar($this);
        }

        return $this;
    }

    public function removeProgressReport(ProgressReport $progressReport): static
    {
        if ($this->progressReports->removeElement($progressReport)) {
            if ($progressReport->getProvisionalCalendar() === $this) {
                $progressReport->setProvisionalCalendar(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'workshopOrder' => $this->workshopOrder->toArray(),
            'deadlinPreparation' => $this->deadlinPreparation,
            'dateEndPreparation' => $this->dateEndPreparation->format('Y-m-d'),
            'deadlinWeave' => $this->deadlinWeave,
            'dateEndWeave' => $this->dateEndWeave->format('Y-m-d'),
            'deadlinFinition' => $this->deadlinFinition,
            'dateEndFinition' => $this->dateEndFinition->format('Y-m-d'),
            'eventPreparation' => $this->eventPreparation,
            'stopPreparation' => $this->stopPreparation,
            'eventWeave' => $this->eventWeave,
            'stopWeave' => $this->stopWeave,
            'eventFinition' => $this->eventFinition,
            'stopFinition' => $this->stopFinition,
        ];
    }


}
