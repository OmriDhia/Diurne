<?php
declare(strict_types=1);

namespace App\CheckingList\Entity;


use App\Workshop\Entity\WorkshopOrder;
use App\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
class CheckingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: WorkshopOrder::class, inversedBy: 'checkingLists')]
    #[ORM\JoinColumn(name: 'workshop_order_id', nullable: false)]
    private WorkshopOrder $workshopOrder;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', nullable: false)]
    private User $author;


    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private \DateTimeInterface $date;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private \DateTimeInterface $dateEndProd;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private string $comment;


    #[ORM\OneToOne(targetEntity: ShapeValidation::class, mappedBy: "checkingList", cascade: ["persist", "remove"])]
    private ?ShapeValidation $shapeValidation = null;

    #[ORM\OneToOne(targetEntity: QualityCheck::class, mappedBy: "checkingList", cascade: ["persist", "remove"])]
    private ?QualityCheck $qualityCheck = null;

    #[ORM\OneToOne(targetEntity: QualityRespect::class, mappedBy: "checkingList", cascade: ["persist", "remove"])]
    private ?QualityRespect $qualityRespect = null;


    #[ORM\OneToMany(targetEntity: LayersValidation::class, mappedBy: "checkingList", cascade: ["persist", "remove"])]
    private Collection $layersValidations;

    public function __construct()
    {
        $this->layersValidations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkshopOrder(): WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(WorkshopOrder $workshopOrder): void
    {
        $this->workshopOrder = $workshopOrder;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getDateEndProd(): \DateTimeInterface
    {
        return $this->dateEndProd;
    }

    public function setDateEndProd(\DateTimeInterface $dateEndProd): void
    {
        $this->dateEndProd = $dateEndProd;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getShapeValidation(): ?ShapeValidation
    {
        return $this->shapeValidation;
    }

    public function setShapeValidation(?ShapeValidation $shapeValidation): void
    {
        $this->shapeValidation = $shapeValidation;
    }

    public function getQualityCheck(): ?QualityCheck
    {
        return $this->qualityCheck;
    }

    public function setQualityCheck(?QualityCheck $qualityCheck): void
    {
        $this->qualityCheck = $qualityCheck;
    }

    public function getQualityRespect(): ?QualityRespect
    {
        return $this->qualityRespect;
    }

    public function setQualityRespect(?QualityRespect $qualityRespect): void
    {
        $this->qualityRespect = $qualityRespect;
    }

    public function getLayersValidations(): Collection
    {
        return $this->layersValidations;
    }

    public function setLayersValidations(Collection $layersValidations): void
    {
        $this->layersValidations = $layersValidations;
    }

    public function addLayersValidation(LayersValidation $layersValidation): static
    {
        if (!$this->layersValidations->contains($layersValidation)) {
            $this->layersValidations->add($layersValidation);
            $layersValidation->setCheckingList($this);
        }

        return $this;
    }

    public function removeLayersValidation(LayersValidation $layersValidation): static
    {
        if ($this->layersValidations->removeElement($layersValidation)) {
            if ($layersValidation->getCheckingList() === $this) {
                $layersValidation->setCheckingList(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author->getId(),
            'date' => $this->date,
            'dateEndProd' => $this->dateEndProd,
            'comment' => $this->comment,
            'workShopOrder' => $this->workshopOrder->toArray(),
            'qualityCheck' => $this->qualityCheck?->toArray(),
            'layersValidations' => array_map(
                fn($validation) => $validation->toArray(),
                $this->layersValidations->toArray()
            ),
            'qualityRespect' => $this->qualityRespect?->toArray(),
            'shapeValidation' => $this->shapeValidation?->toArray(),
        ];
    }

}