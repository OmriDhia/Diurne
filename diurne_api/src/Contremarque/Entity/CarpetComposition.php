<?php

namespace App\Contremarque\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class CarpetComposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $trame = null;

    #[ORM\Column]
    private ?int $threadCount = null;

    #[ORM\Column]
    private ?int $layerCount = null;

    #[ORM\OneToOne(inversedBy: 'carpetComposition', cascade: ['persist', 'remove'])]
    private ?CarpetSpecification $carpetSpecification = null;
    /**
     * @var Collection<int, Layer>
     */
    #[ORM\OneToMany(targetEntity: Layer::class, mappedBy: 'carpetComposition', cascade: ['persist', 'remove'])]
    private Collection $layers;
    /**
     * @var Collection<int, Layer>
     */
    #[ORM\OneToMany(targetEntity: Thread::class, mappedBy: 'carpetComposition', cascade: ['persist', 'remove'])]
    private Collection $threads;

    public function __construct()
    {
        $this->layers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrame(): ?string
    {
        return $this->trame;
    }

    public function setTrame(?string $trame): static
    {
        $this->trame = $trame;

        return $this;
    }

    public function getThreadCount(): ?int
    {
        return $this->threadCount;
    }

    public function setThreadCount(int $threadCount): static
    {
        $this->threadCount = $threadCount;

        return $this;
    }

    public function getLayerCount(): ?int
    {
        return $this->layerCount;
    }

    public function setLayerCount(int $layerCount): static
    {
        $this->layerCount = $layerCount;

        return $this;
    }

    public function getCarpetSpecification(): ?CarpetSpecification
    {
        return $this->carpetSpecification;
    }

    public function setCarpetSpecification(?CarpetSpecification $carpetSpecification): static
    {
        $this->carpetSpecification = $carpetSpecification;

        return $this;
    }

    /**
     * @return Collection<int, Layer>
     */
    public function getLayers(): Collection
    {
        return $this->layers;
    }

    public function addLayer(Layer $layer): static
    {
        if (!$this->layers->contains($layer)) {
            $this->layers->add($layer);
            $layer->setCarpetComposition($this);
        }

        return $this;
    }

    public function removeLayer(Layer $layer): static
    {
        if ($this->layers->removeElement($layer)) {
            // set the owning side to null (unless already changed)
            if ($layer->getCarpetComposition() === $this) {
                $layer->setCarpetComposition(null);
            }
        }

        return $this;
    }

    /**
     * @psalm-return Collection<int, Layer>
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    public function addThread(Thread $thread): static
    {
        if (!$this->threads->contains($thread)) {
            $this->threads->add($thread);
            $thread->setCarpetComposition($this);
        }

        return $this;
    }

    public function removeThread(Layer $thread): static
    {
        if ($this->threads->removeElement($thread)) {
            // set the owning side to null (unless already changed)
            if ($thread->getCarpetComposition() === $this) {
                $thread->setCarpetComposition(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'trame' => $this->trame,
            'threadCount' => $this->threadCount,
            'layerCount' => $this->layerCount,
            'layers' => array_map(fn(Layer $layer) => $layer->toArray(), $this->layers->toArray()),
        ];
    }

    public function resetUniqueFields(): void
    {
        $this->id = null; // Doctrine will auto-generate a new ID
    }
}
