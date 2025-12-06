<?php
declare(strict_types=1);

namespace App\Workshop\Entity;

use App\CheckingList\Entity\CheckingList;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Workshop\Entity\MaterialPurchasePrice;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class WorkshopOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\ManyToOne(
        targetEntity: ImageCommand::class,
        cascade: ['persist', 'remove']
    )]
    #[ORM\JoinColumn(name: 'image_command_id', nullable: true)]
    private ?ImageCommand $imageCommand = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(targetEntity: WorkshopInformation::class, inversedBy: 'workshopOrder')]
    #[ORM\JoinColumn(name: 'workshop_information_id', nullable: false)]
    private ?WorkshopInformation $workshopInformation = null;

    #[ORM\OneToMany(
        targetEntity: WorkshopImage::class,
        mappedBy: 'workshopOrder',
        cascade: ['persist', 'remove'],
        orphanRemoval: true)]
    private Collection $workshopImages;

    #[ORM\OneToMany(
        targetEntity: WorkshopRnHistory::class,
        mappedBy: 'workshopOrder',
        cascade: ['persist', 'remove'],
        orphanRemoval: true)]
    private Collection $workshopRnHistories;

    #[ORM\OneToMany(
        targetEntity: MaterialPurchasePrice::class,
        mappedBy: 'workshopOrder',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $materialPurchasePrices;
    #[ORM\OneToMany(targetEntity: CheckingList::class, mappedBy: 'workshopOrder', cascade: ['persist', 'remove'])]
    private Collection $checkingLists;

    public function __construct()
    {
        $this->workshopImages = new ArrayCollection();
        $this->workshopRnHistories = new ArrayCollection();
        $this->checkingLists = new ArrayCollection();
        $this->materialPurchasePrices = new ArrayCollection();

    }

    public function getCheckingLists(): Collection
    {
        return $this->checkingLists;
    }

    public function addCheckingList(CheckingList $checkingList): self
    {
        if (!$this->checkingLists->contains($checkingList)) {
            $this->checkingLists[] = $checkingList;
            $checkingList->setWorkshopOrder($this);
        }

        return $this;
    }

    public function removeCheckingList(CheckingList $checkingList): self
    {
        if ($this->checkingLists->removeElement($checkingList)) {
            if ($checkingList->getWorkshopOrder() === $this) {
                $checkingList->setWorkshopOrder(null);
            }
        }

        return $this;
    }

    public function getLastCheckingList(): ?CheckingList
    {
        if ($this->checkingLists->isEmpty()) {
            return null;
        }

        $last = null;
        foreach ($this->checkingLists as $checkingList) {
            if ($last === null || ($checkingList->getId() ?? 0) > ($last->getId() ?? 0)) {
                $last = $checkingList;
            }
        }

        return $last;
    }

    public function getWorkshopImages(): Collection
    {
        return $this->workshopImages;
    }

    public function addWorkshopImage(WorkshopImage $workshopImage): self
    {
        if (!$this->workshopImages->contains($workshopImage)) {
            $this->workshopImages[] = $workshopImage;
            $workshopImage->setWorkshopOrder($this);
        }
        return $this;
    }

    public function removeWorkshopImage(WorkshopImage $workshopImage): self
    {
        if ($this->workshopImages->removeElement($workshopImage)) {
            // set the owning side to null (unless already changed)
            if ($workshopImage->getWorkshopOrder() === $this) {
                $workshopImage->setWorkshopOrder(null);
            }
        }
        return $this;
    }

    public function getWorkshopInformation(): ?WorkshopInformation
    {
        return $this->workshopInformation;
    }

    public function setWorkshopInformation(?WorkshopInformation $workshopInformation): self
    {
        $this->workshopInformation = $workshopInformation;

        return $this;
    }

    public function getWorkshopRnHistory(): Collection
    {
        return $this->workshopImages;
    }

    public function addWorkshopRnHistory(WorkshopRnHistory $workshopRnHistory): self
    {
        if (!$this->workshopImages->contains($workshopRnHistory)) {
            $this->workshopRnHistories[] = $workshopRnHistory;
            $workshopRnHistory->setWorkshopOrder($this);
        }
        return $this;
    }

    public function removeWorkshopRnHistory(WorkshopRnHistory $workshopRnHistory): self
    {
        if ($this->workshopRnHistories->removeElement($workshopRnHistory)) {
            // set the owning side to null (unless already changed)
            if ($workshopRnHistory->getWorkshopOrder() === $this) {
                $workshopRnHistory->setWorkshopOrder(null);
            }
        }
        return $this;
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

    public function getImageCommand(): ImageCommand
    {
        return $this->imageCommand;
    }

    public function setWorkshopImages(Collection $workshopImages): void
    {
        $this->workshopImages = $workshopImages;
    }

    public function setImageCommand(?ImageCommand $imageCommand): void
    {
        $this->imageCommand = $imageCommand;
    }


    public function getWorkshopRnHistories(): Collection
    {
        return $this->workshopRnHistories;
    }

    public function setWorkshopRnHistories(Collection $workshopRnHistories): void
    {
        $this->workshopRnHistories = $workshopRnHistories;
    }

    public function getMaterialPurchasePrices(): Collection
    {
        return $this->materialPurchasePrices;
    }

    public function addMaterialPurchasePrice(MaterialPurchasePrice $materialPurchasePrice): self
    {
        if (!$this->materialPurchasePrices->contains($materialPurchasePrice)) {
            $this->materialPurchasePrices[] = $materialPurchasePrice;
            $materialPurchasePrice->setWorkshopOrder($this);
        }

        return $this;
    }

    public function removeMaterialPurchasePrice(MaterialPurchasePrice $materialPurchasePrice): self
    {
        if ($this->materialPurchasePrices->removeElement($materialPurchasePrice)) {
            if ($materialPurchasePrice->getWorkshopOrder() === $this) {
                $materialPurchasePrice->setWorkshopOrder(null);
            }
        }

        return $this;
    }


    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'imageCommand' => $this->imageCommand?->toArray(),
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'workshopInformation' => $this->workshopInformation?->toArray(),
        ];
    }
}
