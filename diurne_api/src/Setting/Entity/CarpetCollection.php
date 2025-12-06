<?php

namespace App\Setting\Entity;

use App\User\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class CarpetCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $show_grid = null;

    #[ORM\ManyToOne]
    private ?SpecialShape $specialShape = null;

    #[ORM\ManyToOne]
    private ?Police $police = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $image_name = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $author = null;

    /**
     * @var Collection<int, CarpetCollectionLang>
     */
    #[ORM\OneToMany(targetEntity: CarpetCollectionLang::class, mappedBy: 'carpetCollection')]
    private Collection $carpetCollectionLang;

    /**
     * @var Collection<int, Model>
     */
    #[ORM\OneToMany(targetEntity: Model::class, mappedBy: 'carpetCollection')]
    private Collection $models;

    #[ORM\ManyToOne(inversedBy: 'carpetCollections')]
    private ?CollectionGroup $collectionGroup = null;

    public function __construct()
    {
        $this->carpetCollectionLang = new ArrayCollection();
        $this->models = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
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

    public function isShowGrid(): ?bool
    {
        return $this->show_grid;
    }

    public function setShowGrid(bool $show_grid): static
    {
        $this->show_grid = $show_grid;

        return $this;
    }

    public function getSpecialShape(): ?SpecialShape
    {
        return $this->specialShape;
    }

    public function setSpecialShape(?SpecialShape $specialShape): static
    {
        $this->specialShape = $specialShape;

        return $this;
    }

    public function getPolice(): ?Police
    {
        return $this->police;
    }

    public function setPolice(?Police $police): static
    {
        $this->police = $police;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): static
    {
        $this->image_name = $image_name;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, CarpetCollectionLang>
     */
    public function getCarpetCollectionLang(): Collection
    {
        return $this->carpetCollectionLang;
    }

    public function addCarpetCollectionLang(CarpetCollectionLang $carpetCollectionLang): static
    {
        if (!$this->carpetCollectionLang->contains($carpetCollectionLang)) {
            $this->carpetCollectionLang->add($carpetCollectionLang);
            $carpetCollectionLang->setCarpetCollection($this);
        }

        return $this;
    }

    public function removeCarpetCollectionLang(CarpetCollectionLang $carpetCollectionLang): static
    {
        if ($this->carpetCollectionLang->removeElement($carpetCollectionLang)) {
            // set the owning side to null (unless already changed)
            if ($carpetCollectionLang->getCarpetCollection() === $this) {
                $carpetCollectionLang->setCarpetCollection(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $carpetCollectionLangs = [];
        if ($this->getCarpetCollectionLang()->count()) {
            foreach ($this->getCarpetCollectionLang() as $index => $object) {
                $carpetCollectionLangs[$index]['id_lang'] = $object->getLanguage()->getId();
                $carpetCollectionLangs[$index]['iso_code'] = $object->getLanguage()->getIsoCode();
                $carpetCollectionLangs[$index]['description'] = $object->getDescription();
            }
        }

        return [
            'id' => $this->getId(),
            'reference' => $this->getReference(),
            'code' => $this->getCode(),
            'show_grid' => $this->isShowGrid(),
            'specialShape' => $this->getSpecialShape() ? $this->getSpecialShape()->getId() : null,
            'police' => $this->getPolice() ? $this->getPolice()->getId() : null,
            'image_name' => $this->getImageName(),
            'author' => $this->getAuthor()?->getId(),
            'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'carpetCollectionLang' => $carpetCollectionLangs,
            'models' => array_map(
                fn (Model $model) => $model->toArray(),
                $this->getModels()->toArray()
            ),
            'collection_group_id' => $this->getCollectionGroup() ? $this->getCollectionGroup()->getId() : null,

        ];
    }

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): static
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
            $model->setCarpetCollection($this);
        }

        return $this;
    }

    public function removeModel(Model $model): static
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getCarpetCollection() === $this) {
                $model->setCarpetCollection(null);
            }
        }

        return $this;
    }

    public function getCollectionGroup(): ?CollectionGroup
    {
        return $this->collectionGroup;
    }

    public function setCollectionGroup(?CollectionGroup $collectionGroup): static
    {
        $this->collectionGroup = $collectionGroup;

        return $this;
    }
}
