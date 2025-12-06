<?php

namespace App\Contremarque\Entity\CarpetOrder;

use App\Workshop\Entity\Carpet;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RnAttribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CarpetOrderDetail::class, inversedBy: 'rnAttributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CarpetOrderDetail $carpetOrderDetail = null;
    #[ORM\ManyToOne(targetEntity: Carpet::class, inversedBy: 'rnAttributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carpet $carpet = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $attributedAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $canceledAt = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $rn = null;

    public function getCanceledAt(): ?\DateTimeInterface
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?\DateTimeInterface $canceledAt): void
    {
        $this->canceledAt = $canceledAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }


    public function getAttributedAt(): \DateTimeInterface
    {
        return $this->attributedAt;
    }

    public function setAttributedAt(\DateTimeInterface $attributedAt): void
    {
        $this->attributedAt = $attributedAt;
    }

    public function getCarpetOrderDetail(): ?CarpetOrderDetail
    {
        return $this->carpetOrderDetail;
    }

    public function setCarpetOrderDetail(?CarpetOrderDetail $carpetOrderDetail): void
    {
        $this->carpetOrderDetail = $carpetOrderDetail;
    }

    public function getCarpet(): ?Carpet
    {
        return $this->carpet;
    }

    public function setCarpet(?Carpet $carpet): void
    {
        $this->carpet = $carpet;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function setRn(?string $rn): void
    {
        $this->rn = $rn;
    }

    public function getWorkshopOrderId(): ?int
    {
        return $this->carpet?->getWorkshopOrder()?->getId();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'attributedAt' => $this->attributedAt->format('Y-m-d H:i:s'),
            'canceledAt' => $this->canceledAt?->format('Y-m-d H:i:s'),
            'carpetOrderDetailId' => $this->carpetOrderDetail?->getId(),
            'carpet' => $this->carpet?->getId(),
            'carpetRnNumber' => $this->carpet?->getRnNumber(),
            'workshopOrderId' => $this->getWorkshopOrderId(),
            'rn' => $this->rn,
        ];
    }

}