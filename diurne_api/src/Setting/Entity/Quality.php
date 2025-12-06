<?php

namespace App\Setting\Entity;

use App\Contremarque\Entity\Sample;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Contremarque\Entity\CarpetSpecification;

#[ORM\Entity]
class Quality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $velvet_standard = null;

    #[ORM\OneToMany(mappedBy: "quality", targetEntity: CarpetSpecification::class)]
    private Collection $carpetSpecifications;
    /**
     * @var Collection<int, QualityLang>
     */
    #[ORM\OneToMany(targetEntity: QualityLang::class, mappedBy: 'quality')]
    private Collection $qualityLangs;

    #[ORM\OneToMany(mappedBy: 'quality', targetEntity: QualityTarifTexture::class)]
    private Collection $qualityTarifTextures;
    #[ORM\OneToMany(mappedBy: "quality", targetEntity: Sample::class)]
    private Collection $samples;


    public function __construct()
    {
        $this->qualityLangs = new ArrayCollection();
        $this->qualityTarifTextures = new ArrayCollection();
        $this->samples = new ArrayCollection();
        $this->carpetSpecifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setQuality($this);
        }
        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->removeElement($sample)) {
            if ($sample->getQuality() === $this) {
                $sample->setQuality(null);
            }
        }
        return $this;
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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getVelvetStandard(): ?string
    {
        return $this->velvet_standard;
    }

    public function setVelvetStandard(?string $velvet_standard): static
    {
        $this->velvet_standard = $velvet_standard;

        return $this;
    }

    /**
     * @return Collection<int, QualityLang>
     */
    public function getQualityLangs(): Collection
    {
        return $this->qualityLangs;
    }

    public function addQualityLang(QualityLang $qualityLang): static
    {
        if (!$this->qualityLangs->contains($qualityLang)) {
            $this->qualityLangs->add($qualityLang);
            $qualityLang->setQuality($this);
        }

        return $this;
    }

    public function removeQualityLang(QualityLang $qualityLang): static
    {
        if ($this->qualityLangs->removeElement($qualityLang)) {
            // set the owning side to null (unless already changed)
            if ($qualityLang->getQuality() === $this) {
                $qualityLang->setQuality(null);
            }
        }

        return $this;
    }


    public function toArray(): array
    {
        $langs = [];
        if ($this->getQualityLangs()->count()) {
            foreach ($this->getQualityLangs() as $lang) {
                $langs[$lang->getLanguage()->getId()]['text'] = $lang->getDescription();
                $langs[$lang->getLanguage()->getId()]['id_lang'] = $lang->getLanguage()->getId();
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'weight' => $this->weight,
            'velvet_standard' => $this->velvet_standard,
            'qualityLangs' => $langs,
        ];
    }

    /**
     * @return Collection<int, QualityTarifTexture>
     */
    public function getQualityTarifTextures(): Collection
    {
        return $this->qualityTarifTextures;
    }

    public function addQualityTarifTexture(QualityTarifTexture $qualityTarifTexture): static
    {
        if (!$this->qualityTarifTextures->contains($qualityTarifTexture)) {
            $this->qualityTarifTextures->add($qualityTarifTexture);
            $qualityTarifTexture->setQuality($this);
        }

        return $this;
    }

    public function removeQualityTarifTexture(QualityTarifTexture $qualityTarifTexture): static
    {
        if ($this->qualityTarifTextures->removeElement($qualityTarifTexture)) {
            if ($qualityTarifTexture->getQuality() === $this) {
                $qualityTarifTexture->setQuality(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, CarpetSpecification>
     */
    public function getCarpetSpecifications(): Collection
    {
        return $this->carpetSpecifications;
    }

    public function addCarpetSpecification(CarpetSpecification $carpetSpecification): static
    {
        if (!$this->carpetSpecifications->contains($carpetSpecification)) {
            $this->carpetSpecifications[] = $carpetSpecification;
            $carpetSpecification->setQuality($this);
        }

        return $this;
    }

    public function removeCarpetSpecification(CarpetSpecification $carpetSpecification): static
    {
        if ($this->carpetSpecifications->removeElement($carpetSpecification)) {
            if ($carpetSpecification->getQuality() === $this) {
                $carpetSpecification->setQuality(null);
            }
        }

        return $this;
    }
}
