<?php

declare(strict_types=1);

namespace App\Setting\Entity;

use App\ProgressReport\Entity\ProcessDeadline;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProgressReportProcess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ProcessDeadline>
     */
    #[ORM\OneToMany(mappedBy: 'process', targetEntity: ProcessDeadline::class)]
    private Collection $processDeadlines;

    public function __construct()
    {
        $this->processDeadlines = new ArrayCollection();
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
            $deadline->setProcess($this);
        }

        return $this;
    }

    public function removeProcessDeadline(ProcessDeadline $deadline): static
    {
        if ($this->processDeadlines->removeElement($deadline)) {
            if ($deadline->getProcess() === $this) {
                $deadline->setProcess(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
