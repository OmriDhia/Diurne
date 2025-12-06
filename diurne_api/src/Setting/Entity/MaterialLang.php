<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MaterialLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Language $language = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'materialLangs')]
    private ?Material $material = null;

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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;

        return $this;
    }

    /**
     * @return (int|mixed|null|string)[]
     *
     * @psalm-return array{id: int|null, language: mixed, label: null|string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'language' => $this->language->toArray(),
            'label' => $this->label,
        ];
    }
}
