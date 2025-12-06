<?php
declare(strict_types=1);

namespace App\ProgressReport\Entity;


use App\User\Entity\User;
use App\ProgressReport\Entity\ProvisionalCalendar;
use App\ProgressReport\Entity\ProcessDeadline;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProgressReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $datePR = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', nullable: false)]
    private User $author;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEvent = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateWorkshop = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $tissage = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ProgressReportStatus $status;

    #[ORM\ManyToOne(targetEntity: ProvisionalCalendar::class, inversedBy: 'progressReports')]
    private ?ProvisionalCalendar $provisionalCalendar = null;

    /**
     * @var Collection<int, ProcessDeadline>
     */
    #[ORM\OneToMany(mappedBy: 'progressReport', targetEntity: ProcessDeadline::class, orphanRemoval: true)]
    private Collection $processDeadlines;

    public function __construct()
    {
        $this->processDeadlines = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getDatePR(): ?\DateTimeInterface
    {
        return $this->datePR;
    }

    public function setDatePR(?\DateTimeInterface $datePR): void
    {
        $this->datePR = $datePR;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(?\DateTimeInterface $dateEvent): void
    {
        $this->dateEvent = $dateEvent;
    }

    public function getDateWorkshop(): ?\DateTimeInterface
    {
        return $this->dateWorkshop;
    }

    public function setDateWorkshop(?\DateTimeInterface $dateWorkshop): void
    {
        $this->dateWorkshop = $dateWorkshop;
    }

    public function getTissage(): ?string
    {
        return $this->tissage;
    }

    public function setTissage(?string $tissage): void
    {
        $this->tissage = $tissage;
    }

    public function getStatus(): ProgressReportStatus
    {
        return $this->status;
    }

    public function setStatus(ProgressReportStatus $status): void
    {
        $this->status = $status;
    }

    public function getProvisionalCalendar(): ?ProvisionalCalendar
    {
        return $this->provisionalCalendar;
    }

    public function setProvisionalCalendar(?ProvisionalCalendar $calendar): void
    {
        $this->provisionalCalendar = $calendar;
    }

    /**
     * @return Collection<int, ProcessDeadline>
     */
    public function getProcessDeadlines(): Collection
    {
        return $this->processDeadlines;
    }

    public function addProcessDeadline(ProcessDeadline $deadline): static
    {
        if (!$this->processDeadlines->contains($deadline)) {
            $this->processDeadlines->add($deadline);
            $deadline->setProgressReport($this);
        }

        return $this;
    }

    public function removeProcessDeadline(ProcessDeadline $deadline): static
    {
        if ($this->processDeadlines->removeElement($deadline)) {
            if ($deadline->getProgressReport() === $this) {
                $deadline->setProgressReport(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'datePR' => $this->datePR?->format('Y-m-d'),
            'author' => $this->author->getId(),
            'comment' => $this->comment,
            'dateEvent' => $this->dateEvent?->format('Y-m-d'),
            'dateWorkshop' => $this->dateWorkshop?->format('Y-m-d'),
            'tissage' => $this->tissage,
            'status' => $this->status->toArray(),
            'provisionalCalendar' => $this->provisionalCalendar?->getId(),
            'processDeadlines' => $this->processDeadlines->map(fn(ProcessDeadline $d) => $d->toArray())->toArray(),
        ];
    }


}
