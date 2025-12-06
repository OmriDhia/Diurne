<?php

namespace App\Setting\Entity;

use App\Contremarque\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ImageType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'imageType')]
    private Collection $images;

    #[ORM\Column(nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: ImageCategoryEnum::class, nullable: true)]
    private ?ImageCategoryEnum $category = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getCategory(): ?ImageCategoryEnum
    {
        return $this->category;
    }

    public function setCategory(?ImageCategoryEnum $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return (int|null|string)[]
     *
     * @psalm-return array{id: int|null, name: null|string, description: null|string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setImageType($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getImageType() === $this) {
                $image->setImageType(null);
            }
        }

        return $this;
    }
}
