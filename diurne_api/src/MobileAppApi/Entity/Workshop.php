<?php

declare(strict_types=1);

namespace App\MobileAppApi\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Workshop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $carpetRnPrefix = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $sampleRnPrefix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCarpetRnPrefix(): ?string
    {
        return $this->carpetRnPrefix;
    }

    public function setCarpetRnPrefix(?string $carpetRnPrefix): void
    {
        $this->carpetRnPrefix = $carpetRnPrefix;
    }

    public function getSampleRnPrefix(): ?string
    {
        return $this->sampleRnPrefix;
    }

    public function setSampleRnPrefix(?string $sampleRnPrefix): void
    {
        $this->sampleRnPrefix = $sampleRnPrefix;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'carpetRnPrefix' => $this->carpetRnPrefix,
            'sampleRnPrefix' => $this->sampleRnPrefix,
        ];
    }
}
