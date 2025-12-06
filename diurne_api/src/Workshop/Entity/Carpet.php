<?php

namespace App\Workshop\Entity;


use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Workshop\Entity\WorkshopOrder;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Carpet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $rnNumber;

    #[ORM\OneToOne(targetEntity: WorkshopRnHistory::class, mappedBy: 'carpet')]
    private ?WorkshopRnHistory $workshopRnHistory = null;

    #[ORM\ManyToOne(targetEntity: ImageCommand::class, inversedBy: 'carpets')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?ImageCommand $imageCommand = null;

    #[ORM\ManyToOne(targetEntity: WorkshopOrder::class)]
    #[ORM\JoinColumn(name: 'workshop_order_id', nullable: true)]
    private ?WorkshopOrder $workshopOrder = null;

    #[ORM\ManyToOne(targetEntity: CarpetOrderDetail::class, inversedBy: 'carpets')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CarpetOrderDetail $carpetOrderDetail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkshopRnHistory(): ?WorkshopRnHistory
    {
        return $this->workshopRnHistory;
    }

    public function setWorkshopRnHistory(?WorkshopRnHistory $workshopRnHistory): void
    {
        $this->workshopRnHistory = $workshopRnHistory;
    }

    public function getRnNumber(): string
    {
        return $this->rnNumber;
    }

    public function setRnNumber(string $rnNumber): void
    {
        $this->rnNumber = $rnNumber;
    }

    public function getImageCommand(): ?ImageCommand
    {
        return $this->imageCommand;
    }

    public function setImageCommand(?ImageCommand $imageCommand): void
    {
        $this->imageCommand = $imageCommand;
    }

    public function getCarpetOrderDetail(): ?CarpetOrderDetail
    {
        return $this->carpetOrderDetail;
    }

    public function setCarpetOrderDetail(?CarpetOrderDetail $carpetOrderDetail): void
    {
        $this->carpetOrderDetail = $carpetOrderDetail;
    }

    public function getWorkshopOrder(): ?WorkshopOrder
    {
        return $this->workshopOrder;
    }

    public function setWorkshopOrder(?WorkshopOrder $workshopOrder): void
    {
        $this->workshopOrder = $workshopOrder;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rnNumber' => $this->rnNumber,
            // return lightweight summaries to avoid huge payloads
            'workshopRnHistory' => $this->workshopRnHistory ? [
                'id' => $this->workshopRnHistory->getId(),
                'beginAt' => $this->workshopRnHistory->getBeginAt()?->format('Y-m-d H:i:s'),
                'endAt' => $this->workshopRnHistory->getEndAt()?->format('Y-m-d H:i:s'),
            ] : null,
            'imageCommand' => $this->imageCommand ? [
                'id' => $this->imageCommand->getId(),
                // only include tiny metadata, avoid full attachment/paths
                'createdAt' => method_exists($this->imageCommand, 'getCreatedAt') ? $this->imageCommand->getCreatedAt()?->format('Y-m-d H:i:s') : null,
            ] : null,
            'carpetOrderDetail' => $this->carpetOrderDetail ? [
                'id' => $this->carpetOrderDetail->getId(),
                'quoteDetailId' => method_exists($this->carpetOrderDetail, 'getQuoteDetailId') && $this->carpetOrderDetail->getQuoteDetailId() ? $this->carpetOrderDetail->getQuoteDetailId()->getId() : null,
            ] : null,
            'workshopOrder' => $this->workshopOrder ? [
                'id' => $this->workshopOrder->getId(),
                'workshopInformation' => ($this->workshopOrder->getWorkshopInformation() ? [
                    'carpetPurchasePricePerM2' => $this->workshopOrder->getWorkshopInformation()->getCarpetPurchasePricePerM2(),
                    'realSurface' => $this->workshopOrder->getWorkshopInformation()->getRealSurface(),
                    'orderedSurface' => $this->workshopOrder->getWorkshopInformation()->getOrderedSurface(),
                    'penalty' => $this->workshopOrder->getWorkshopInformation()->getPenalty(),
                    'quality' => $this->workshopOrder->getWorkshopInformation()->getQuality() ? [
                        'weight' => $this->workshopOrder->getWorkshopInformation()->getQuality()->getWeight()
                    ] : null,
                ] : null),
            ] : null,
        ];
    }

}