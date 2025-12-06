<?php

namespace App\Setting\Entity;

use App\Contremarque\Entity\Sample;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'integer')]
    private ?int $number_max = null;

    #[ORM\ManyToOne(inversedBy: 'models')]
    private ?CarpetCollection $carpetCollection = null;
    #[ORM\OneToMany(mappedBy: "model", targetEntity: Sample::class)]
    private Collection $samples;

    public function __construct()
    {
        $this->samples = new ArrayCollection();
    }

    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setModel($this);
        }
        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->removeElement($sample)) {
            if ($sample->getModel() === $this) {
                $sample->setModel(null);
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'number_max' => $this->getNumberMax(),
            'carpet_collection_id' => $this->getCarpetCollection()?->getId(),
            'carpet_collection' => $this->getCarpetCollection() ? [
                'id' => $this->getCarpetCollection()->getId(),
                'reference' => $this->getCarpetCollection()->getReference(),
                'code' => $this->getCarpetCollection()->getCode(),
            ] : null,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNumberMax(): ?int
    {
        return $this->number_max;
    }

    public function setNumberMax(int $number_max): static
    {
        $this->number_max = $number_max;

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
}
