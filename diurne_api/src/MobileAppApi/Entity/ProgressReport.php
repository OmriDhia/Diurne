<?php

declare(strict_types=1);

namespace App\MobileAppApi\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\UserMobileApp;
use DateTimeImmutable;

#[ORM\Entity]
class ProgressReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: RN::class, inversedBy: 'progressReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RN $rn = null;

    // States: Préparation, Tissage, Finition, Envoi
    #[ORM\Column(type: 'string', length: 50)]
    private string $state;

    #[ORM\Column(type: 'boolean')]
    private bool $isWoven = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $date;

    #[ORM\ManyToOne(targetEntity: UserMobileApp::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserMobileApp $user = null;

    public function __construct()
    {
        $this->date = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRn(): ?RN
    {
        return $this->rn;
    }

    public function setRn(?RN $rn): void
    {
        $this->rn = $rn;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function isWoven(): bool
    {
        return $this->isWoven;
    }

    public function setIsWoven(bool $isWoven): void
    {
        $this->isWoven = $isWoven;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getUser(): ?UserMobileApp
    {
        return $this->user;
    }

    public function setUser(?UserMobileApp $user): void
    {
        $this->user = $user;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rn' => $this->rn?->toArray(),
            'state' => $this->state,
            'isWoven' => $this->isWoven,
            'comment' => $this->comment,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'user' => $this->user?->getId(),
        ];
    }
}
