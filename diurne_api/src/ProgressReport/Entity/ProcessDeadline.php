<?php

declare(strict_types=1);

namespace App\ProgressReport\Entity;

use App\Setting\Entity\ProgressReportProcess;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProcessDeadline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'processDeadlines')]
    #[ORM\JoinColumn(nullable: false)]
    private ProgressReport $progressReport;

    #[ORM\ManyToOne(inversedBy: 'processDeadlines')]
    #[ORM\JoinColumn(nullable: false)]
    private ProgressReportProcess $process;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgressReport(): ProgressReport
    {
        return $this->progressReport;
    }

    public function setProgressReport(ProgressReport $progressReport): static
    {
        $this->progressReport = $progressReport;

        return $this;
    }

    public function getProcess(): ProgressReportProcess
    {
        return $this->process;
    }

    public function setProcess(?ProgressReportProcess $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'process' => $this->process->toArray(),
            'dateStart' => $this->dateStart?->format('Y-m-d'),
            'dateEnd' => $this->dateEnd?->format('Y-m-d'),
            'comment' => $this->comment,
        ];
    }
}
