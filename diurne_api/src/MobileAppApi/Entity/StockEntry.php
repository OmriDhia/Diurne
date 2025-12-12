<?php

declare(strict_types=1);

namespace App\MobileAppApi\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\UserMobileApp;
use DateTimeImmutable;

#[ORM\Entity]
class StockEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: RN::class, inversedBy: 'entries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RN $rn = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $location;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $date;

    #[ORM\ManyToOne(targetEntity: UserMobileApp::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserMobileApp $user = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $width = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $height = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $quality = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $color = null;

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

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
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

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }

    public function getQuality(): ?string
    {
        return $this->quality;
    }

    public function setQuality(?string $quality): void
    {
        $this->quality = $quality;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rn' => $this->rn?->toArray(),
            'location' => $this->location,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'user' => $this->user?->getId(),
            'width' => $this->width,
            'height' => $this->height,
            'quality' => $this->quality,
            'color' => $this->color,
        ];
    }
}
