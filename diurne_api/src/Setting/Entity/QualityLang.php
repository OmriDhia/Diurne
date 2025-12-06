<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class QualityLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Language $language = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'qualityLang')]
    private ?Quality $quality = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuality(): ?Quality
    {
        return $this->quality;
    }

    public function setQuality(?Quality $quality): static
    {
        $this->quality = $quality;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'id_lang' => $this->language ? $this->language->getId() : null,
            'iso_code' => $this->language ? $this->language->getIsoCode() : null,
            'description' => $this->description,
            'quality' => $this->quality ? $this->quality->getId() : null,
        ];
    }
}
