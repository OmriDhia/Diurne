<?php

namespace App\Contremarque\Entity\ImageCommand;

use DateTimeInterface;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ImageCommandDesignerAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: ImageCommand::class, inversedBy: 'imageCommandDesignerAssignments')]
    private ?ImageCommand $imageCommand = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'assignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $designer = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $from_datetime;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $to_datetime;
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $inProgress = false;
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $stopped = false;
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $reasonForStopping = null;
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $done = false;

    public function getImageCommand(): ?ImageCommand
    {
        return $this->imageCommand;
    }

    public function setImageCommand(?ImageCommand $imageCommand): void
    {
        $this->imageCommand = $imageCommand;
    }

    public function getDesigner(): ?User
    {
        return $this->designer;
    }

    public function setDesigner(?User $designer): void
    {
        $this->designer = $designer;
    }

    public function getFromDatetime(): DateTimeInterface
    {
        return $this->from_datetime;
    }

    public function setFromDatetime(DateTimeInterface $from_datetime): void
    {
        $this->from_datetime = $from_datetime;
    }

    public function getToDatetime(): DateTimeInterface
    {
        return $this->to_datetime;
    }

    public function setToDatetime(DateTimeInterface $to_datetime): void
    {
        $this->to_datetime = $to_datetime;
    }

    public function isInProgress(): bool
    {
        return $this->inProgress;
    }

    public function setInProgress(bool $inProgress): void
    {
        $this->inProgress = $inProgress;
    }

    public function isStopped(): bool
    {
        return $this->stopped;
    }

    public function setStopped(bool $stopped): void
    {
        $this->stopped = $stopped;
    }

    public function getReasonForStopping(): ?string
    {
        return $this->reasonForStopping;
    }

    public function setReasonForStopping(?string $reasonForStopping): void
    {
        $this->reasonForStopping = $reasonForStopping;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function setDone(bool $done): void
    {
        $this->done = $done;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'imageCommand' => $this->imageCommand->getId(),
            'designer' => $this->designer->getId(),
            'from_datetime' => $this->from_datetime->format('Y-m-d H:i:s'),
            'to_datetime' => $this->to_datetime->format('Y-m-d H:i:s'),
            'inProgress' => $this->inProgress,
            'stopped' => $this->stopped,
            'reasonForStopping' => $this->reasonForStopping,
            'done' => $this->done,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
