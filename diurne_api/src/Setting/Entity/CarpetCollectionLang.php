<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetCollectionLang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    private ?Language $language = null;

    #[ORM\ManyToOne(inversedBy: 'carpetCollectionLang')]
    private ?CarpetCollection $carpetCollection = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCarpetCollection(): ?CarpetCollection
    {
        return $this->carpetCollection;
    }

    public function setCarpetCollection(?CarpetCollection $carpetCollection): static
    {
        $this->carpetCollection = $carpetCollection;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'description' => $this->getDescription(),
            'language' => $this->getLanguage() ? $this->getLanguage()->toArray() : null,
            'carpetCollection' => $this->getCarpetCollection() ? $this->getCarpetCollection()->toArray() : null,
        ];
    }
}
