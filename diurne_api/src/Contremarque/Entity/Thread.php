<?php

namespace App\Contremarque\Entity;

use App\Setting\Entity\DominantColor;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Thread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $thread_number = null;

    #[ORM\ManyToOne]
    private ?DominantColor $techColor = null;

    #[ORM\ManyToOne(targetEntity: CarpetComposition::class, inversedBy: 'threads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetComposition $carpetComposition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThreadNumber(): ?int
    {
        return $this->thread_number;
    }

    public function setThreadNumber(int $thread_number): static
    {
        $this->thread_number = $thread_number;

        return $this;
    }

    public function getTechColor(): ?DominantColor
    {
        return $this->techColor;
    }

    public function setTechColor(?DominantColor $techColor): static
    {
        $this->techColor = $techColor;

        return $this;
    }

    public function getCarpetComposition(): ?CarpetComposition
    {
        return $this->carpetComposition;
    }

    public function setCarpetComposition(?CarpetComposition $carpetComposition): static
    {
        $this->carpetComposition = $carpetComposition;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'thread_number' => $this->getThreadNumber(),
            'name' => $this->getTechColor() ? $this->getTechColor()->getName() : null,
            'hexCode' => $this->getTechColor() ? $this->getTechColor()->getHexCode() : null,
            'carpet_composition' => $this->getCarpetComposition() ? $this->getCarpetComposition()->getId() : null,
        ];
    }
}
