<?php
declare(strict_types=1);

namespace App\CheckingList\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class QualityRespect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    #[ORM\OneToOne(inversedBy: "qualityRespect")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private CheckingList $checkingList;

    // Respect Plan
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectPlanRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectPlanValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectPlanSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectPlanComment = null;

    // Respect Door Height
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectDoorHeightRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectDoorHeightValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectDoorHeightSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectDoorHeightComment = null;

    // Respect Max Min Length
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinLengthRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinLengthValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinLengthSeen = null;

    // Respect Max Min Width
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinWidthRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinWidthValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaxMinWidthSeen = null;

    // Wall Distance Right
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceRightRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceRightValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceRightSeen = null;

    // Wall Distance Left
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceLeftRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceLeftValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectwallDistanceLeftSeen = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 6, nullable: true)]
    private ?string $penalty = null;

    // Respect Foss
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectFossRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectFossValide = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectFossSeen = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $respectFossComment = null;

    // Respect Other Carpet
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectOtherCarpetRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectOtherCarpetValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectOtherCarpetSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectOtherCarpetComment = null;

    // Respect Color
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectColorRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectColorValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectColorSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectColorComment = null;

    // Respect Material
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaterialRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaterialValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectMaterialSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectMaterialComment = null;

    // Respect Remark
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectRemarkRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectRemarkValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectRemarkSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectRemarkComment = null;

    // Respect Velour
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectVelourRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectVelourValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $respectVelourSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $respectVelourComment = null;

    // Wall Distance Top
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceTopRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceTopValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceTopSeen = null;

    // Wall Distance Bottom
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceBottomRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceBottomValid = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $wallDistanceBottomSeen = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $orderStatus = null;

    // Getters and Setters for Respect Plan
    public function isRespectPlanRelevant(): ?bool
    {
        return $this->respectPlanRelevant;
    }

    public function setRespectPlanRelevant(?bool $respectPlanRelevant): void
    {
        $this->respectPlanRelevant = $respectPlanRelevant;
    }

    public function isRespectPlanValid(): ?bool
    {
        return $this->respectPlanValid;
    }

    public function setRespectPlanValid(?bool $respectPlanValid): void
    {
        $this->respectPlanValid = $respectPlanValid;
    }

    public function isRespectPlanSeen(): ?bool
    {
        return $this->respectPlanSeen;
    }

    public function setRespectPlanSeen(?bool $respectPlanSeen): void
    {
        $this->respectPlanSeen = $respectPlanSeen;
    }

    public function getRespectPlanComment(): ?string
    {
        return $this->respectPlanComment;
    }

    public function setRespectPlanComment(?string $respectPlanComment): void
    {
        $this->respectPlanComment = $respectPlanComment;
    }

    // Getters and Setters for Respect Door Height
    public function isRespectDoorHeightRelevant(): ?bool
    {
        return $this->respectDoorHeightRelevant;
    }

    public function setRespectDoorHeightRelevant(?bool $respectDoorHeightRelevant): void
    {
        $this->respectDoorHeightRelevant = $respectDoorHeightRelevant;
    }

    public function isRespectDoorHeightValid(): ?bool
    {
        return $this->respectDoorHeightValid;
    }

    public function setRespectDoorHeightValid(?bool $respectDoorHeightValid): void
    {
        $this->respectDoorHeightValid = $respectDoorHeightValid;
    }

    public function isRespectDoorHeightSeen(): ?bool
    {
        return $this->respectDoorHeightSeen;
    }

    public function setRespectDoorHeightSeen(?bool $respectDoorHeightSeen): void
    {
        $this->respectDoorHeightSeen = $respectDoorHeightSeen;
    }

    public function getRespectDoorHeightComment(): ?string
    {
        return $this->respectDoorHeightComment;
    }

    public function setRespectDoorHeightComment(?string $respectDoorHeightComment): void
    {
        $this->respectDoorHeightComment = $respectDoorHeightComment;
    }

    // Getters and Setters for Respect Max Min Length
    public function isRespectMaxMinLengthRelevant(): ?bool
    {
        return $this->respectMaxMinLengthRelevant;
    }

    public function setRespectMaxMinLengthRelevant(?bool $respectMaxMinLengthRelevant): void
    {
        $this->respectMaxMinLengthRelevant = $respectMaxMinLengthRelevant;
    }

    public function isRespectMaxMinLengthValid(): ?bool
    {
        return $this->respectMaxMinLengthValid;
    }

    public function setRespectMaxMinLengthValid(?bool $respectMaxMinLengthValid): void
    {
        $this->respectMaxMinLengthValid = $respectMaxMinLengthValid;
    }

    public function isRespectMaxMinLengthSeen(): ?bool
    {
        return $this->respectMaxMinLengthSeen;
    }

    public function setRespectMaxMinLengthSeen(?bool $respectMaxMinLengthSeen): void
    {
        $this->respectMaxMinLengthSeen = $respectMaxMinLengthSeen;
    }

    // Getters and Setters for Respect Max Min Width
    public function isRespectMaxMinWidthRelevant(): ?bool
    {
        return $this->respectMaxMinWidthRelevant;
    }

    public function setRespectMaxMinWidthRelevant(?bool $respectMaxMinWidthRelevant): void
    {
        $this->respectMaxMinWidthRelevant = $respectMaxMinWidthRelevant;
    }

    public function isRespectMaxMinWidthValid(): ?bool
    {
        return $this->respectMaxMinWidthValid;
    }

    public function setRespectMaxMinWidthValid(?bool $respectMaxMinWidthValid): void
    {
        $this->respectMaxMinWidthValid = $respectMaxMinWidthValid;
    }

    public function isRespectMaxMinWidthSeen(): ?bool
    {
        return $this->respectMaxMinWidthSeen;
    }

    public function setRespectMaxMinWidthSeen(?bool $respectMaxMinWidthSeen): void
    {
        $this->respectMaxMinWidthSeen = $respectMaxMinWidthSeen;
    }

    // Getters and Setters for Wall Distance Right
    public function isRespectwallDistanceRightRelevant(): ?bool
    {
        return $this->respectwallDistanceRightRelevant;
    }

    public function setRespectwallDistanceRightRelevant(?bool $respectwallDistanceRightRelevant): void
    {
        $this->respectwallDistanceRightRelevant = $respectwallDistanceRightRelevant;
    }

    public function isRespectwallDistanceRightValid(): ?bool
    {
        return $this->respectwallDistanceRightValid;
    }

    public function setRespectwallDistanceRightValid(?bool $respectwallDistanceRightValid): void
    {
        $this->respectwallDistanceRightValid = $respectwallDistanceRightValid;
    }

    public function isRespectwallDistanceRightSeen(): ?bool
    {
        return $this->respectwallDistanceRightSeen;
    }

    public function setRespectwallDistanceRightSeen(?bool $respectwallDistanceRightSeen): void
    {
        $this->respectwallDistanceRightSeen = $respectwallDistanceRightSeen;
    }

    // Getters and Setters for Wall Distance Left
    public function isRespectwallDistanceLeftRelevant(): ?bool
    {
        return $this->respectwallDistanceLeftRelevant;
    }

    public function setRespectwallDistanceLeftRelevant(?bool $respectwallDistanceLeftRelevant): void
    {
        $this->respectwallDistanceLeftRelevant = $respectwallDistanceLeftRelevant;
    }

    public function isRespectwallDistanceLeftValid(): ?bool
    {
        return $this->respectwallDistanceLeftValid;
    }

    public function setRespectwallDistanceLeftValid(?bool $respectwallDistanceLeftValid): void
    {
        $this->respectwallDistanceLeftValid = $respectwallDistanceLeftValid;
    }

    public function isRespectwallDistanceLeftSeen(): ?bool
    {
        return $this->respectwallDistanceLeftSeen;
    }

    public function setRespectwallDistanceLeftSeen(?bool $respectwallDistanceLeftSeen): void
    {
        $this->respectwallDistanceLeftSeen = $respectwallDistanceLeftSeen;
    }

    public function getPenalty(): ?string
    {
        return $this->penalty;
    }

    public function setPenalty(?string $penalty): void
    {
        $this->penalty = $penalty;
    }

    // Getters and Setters for Respect Foss
    public function isRespectFossRelevant(): ?bool
    {
        return $this->respectFossRelevant;
    }

    public function setRespectFossRelevant(?bool $respectFossRelevant): void
    {
        $this->respectFossRelevant = $respectFossRelevant;
    }

    public function isRespectFossValide(): ?bool
    {
        return $this->respectFossValide;
    }

    public function setRespectFossValide(?bool $respectFossValide): void
    {
        $this->respectFossValide = $respectFossValide;
    }

    public function isRespectFossSeen(): ?bool
    {
        return $this->respectFossSeen;
    }

    public function setRespectFossSeen(?bool $respectFossSeen): void
    {
        $this->respectFossSeen = $respectFossSeen;
    }

    public function getRespectFossComment(): ?string
    {
        return $this->respectFossComment;
    }

    public function setRespectFossComment(?string $respectFossComment): void
    {
        $this->respectFossComment = $respectFossComment;
    }

    // Getters and Setters for Respect Other Carpet
    public function isRespectOtherCarpetRelevant(): ?bool
    {
        return $this->respectOtherCarpetRelevant;
    }

    public function setRespectOtherCarpetRelevant(?bool $respectOtherCarpetRelevant): void
    {
        $this->respectOtherCarpetRelevant = $respectOtherCarpetRelevant;
    }

    public function isRespectOtherCarpetValid(): ?bool
    {
        return $this->respectOtherCarpetValid;
    }

    public function setRespectOtherCarpetValid(?bool $respectOtherCarpetValid): void
    {
        $this->respectOtherCarpetValid = $respectOtherCarpetValid;
    }

    public function isRespectOtherCarpetSeen(): ?bool
    {
        return $this->respectOtherCarpetSeen;
    }

    public function setRespectOtherCarpetSeen(?bool $respectOtherCarpetSeen): void
    {
        $this->respectOtherCarpetSeen = $respectOtherCarpetSeen;
    }

    public function getRespectOtherCarpetComment(): ?string
    {
        return $this->respectOtherCarpetComment;
    }

    public function setRespectOtherCarpetComment(?string $respectOtherCarpetComment): void
    {
        $this->respectOtherCarpetComment = $respectOtherCarpetComment;
    }

    // Getters and Setters for Respect Color
    public function isRespectColorRelevant(): ?bool
    {
        return $this->respectColorRelevant;
    }

    public function setRespectColorRelevant(?bool $respectColorRelevant): void
    {
        $this->respectColorRelevant = $respectColorRelevant;
    }

    public function isRespectColorValid(): ?bool
    {
        return $this->respectColorValid;
    }

    public function setRespectColorValid(?bool $respectColorValid): void
    {
        $this->respectColorValid = $respectColorValid;
    }

    public function isRespectColorSeen(): ?bool
    {
        return $this->respectColorSeen;
    }

    public function setRespectColorSeen(?bool $respectColorSeen): void
    {
        $this->respectColorSeen = $respectColorSeen;
    }

    public function getRespectColorComment(): ?string
    {
        return $this->respectColorComment;
    }

    public function setRespectColorComment(?string $respectColorComment): void
    {
        $this->respectColorComment = $respectColorComment;
    }

    // Getters and Setters for Respect Material
    public function isRespectMaterialRelevant(): ?bool
    {
        return $this->respectMaterialRelevant;
    }

    public function setRespectMaterialRelevant(?bool $respectMaterialRelevant): void
    {
        $this->respectMaterialRelevant = $respectMaterialRelevant;
    }

    public function isRespectMaterialValid(): ?bool
    {
        return $this->respectMaterialValid;
    }

    public function setRespectMaterialValid(?bool $respectMaterialValid): void
    {
        $this->respectMaterialValid = $respectMaterialValid;
    }

    public function isRespectMaterialSeen(): ?bool
    {
        return $this->respectMaterialSeen;
    }

    public function setRespectMaterialSeen(?bool $respectMaterialSeen): void
    {
        $this->respectMaterialSeen = $respectMaterialSeen;
    }

    public function getRespectMaterialComment(): ?string
    {
        return $this->respectMaterialComment;
    }

    public function setRespectMaterialComment(?string $respectMaterialComment): void
    {
        $this->respectMaterialComment = $respectMaterialComment;
    }

    // Getters and Setters for Respect Remark
    public function isRespectRemarkRelevant(): ?bool
    {
        return $this->respectRemarkRelevant;
    }

    public function setRespectRemarkRelevant(?bool $respectRemarkRelevant): void
    {
        $this->respectRemarkRelevant = $respectRemarkRelevant;
    }

    public function isRespectRemarkValid(): ?bool
    {
        return $this->respectRemarkValid;
    }

    public function setRespectRemarkValid(?bool $respectRemarkValid): void
    {
        $this->respectRemarkValid = $respectRemarkValid;
    }

    public function isRespectRemarkSeen(): ?bool
    {
        return $this->respectRemarkSeen;
    }

    public function setRespectRemarkSeen(?bool $respectRemarkSeen): void
    {
        $this->respectRemarkSeen = $respectRemarkSeen;
    }

    public function getRespectRemarkComment(): ?string
    {
        return $this->respectRemarkComment;
    }

    public function setRespectRemarkComment(?string $respectRemarkComment): void
    {
        $this->respectRemarkComment = $respectRemarkComment;
    }

    // Getters and Setters for Respect Velour
    public function isRespectVelourRelevant(): ?bool
    {
        return $this->respectVelourRelevant;
    }

    public function setRespectVelourRelevant(?bool $respectVelourRelevant): void
    {
        $this->respectVelourRelevant = $respectVelourRelevant;
    }

    public function isRespectVelourValid(): ?bool
    {
        return $this->respectVelourValid;
    }

    public function setRespectVelourValid(?bool $respectVelourValid): void
    {
        $this->respectVelourValid = $respectVelourValid;
    }

    public function isRespectVelourSeen(): ?bool
    {
        return $this->respectVelourSeen;
    }

    public function setRespectVelourSeen(?bool $respectVelourSeen): void
    {
        $this->respectVelourSeen = $respectVelourSeen;
    }

    public function getRespectVelourComment(): ?string
    {
        return $this->respectVelourComment;
    }

    public function setRespectVelourComment(?string $respectVelourComment): void
    {
        $this->respectVelourComment = $respectVelourComment;
    }

    // Getters and Setters for Wall Distance Top
    public function isWallDistanceTopRelevant(): ?bool
    {
        return $this->wallDistanceTopRelevant;
    }

    public function setWallDistanceTopRelevant(?bool $wallDistanceTopRelevant): void
    {
        $this->wallDistanceTopRelevant = $wallDistanceTopRelevant;
    }

    public function isWallDistanceTopValid(): ?bool
    {
        return $this->wallDistanceTopValid;
    }

    public function setWallDistanceTopValid(?bool $wallDistanceTopValid): void
    {
        $this->wallDistanceTopValid = $wallDistanceTopValid;
    }

    public function isWallDistanceTopSeen(): ?bool
    {
        return $this->wallDistanceTopSeen;
    }

    public function setWallDistanceTopSeen(?bool $wallDistanceTopSeen): void
    {
        $this->wallDistanceTopSeen = $wallDistanceTopSeen;
    }

    // Getters and Setters for Wall Distance Bottom
    public function isWallDistanceBottomRelevant(): ?bool
    {
        return $this->wallDistanceBottomRelevant;
    }

    public function setWallDistanceBottomRelevant(?bool $wallDistanceBottomRelevant): void
    {
        $this->wallDistanceBottomRelevant = $wallDistanceBottomRelevant;
    }

    public function isWallDistanceBottomValid(): ?bool
    {
        return $this->wallDistanceBottomValid;
    }

    public function setWallDistanceBottomValid(?bool $wallDistanceBottomValid): void
    {
        $this->wallDistanceBottomValid = $wallDistanceBottomValid;
    }

    public function isWallDistanceBottomSeen(): ?bool
    {
        return $this->wallDistanceBottomSeen;
    }

    public function setWallDistanceBottomSeen(?bool $wallDistanceBottomSeen): void
    {
        $this->wallDistanceBottomSeen = $wallDistanceBottomSeen;
    }

    public function isOrderStatus(): ?bool
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?bool $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCheckingList(): CheckingList
    {
        return $this->checkingList;
    }

    public function setCheckingList(CheckingList $checkingList): void
    {
        $this->checkingList = $checkingList;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'respectPlanRelevant' => $this->respectPlanRelevant,
            'respectPlanValid' => $this->respectPlanValid,
            'respectPlanSeen' => $this->respectPlanSeen,
            'respectPlanComment' => $this->respectPlanComment,
            'respectDoorHeightRelevant' => $this->respectDoorHeightRelevant,
            'respectDoorHeightValid' => $this->respectDoorHeightValid,
            'respectDoorHeightSeen' => $this->respectDoorHeightSeen,
            'respectDoorHeightComment' => $this->respectDoorHeightComment,
            'respectMaxMinLengthRelevant' => $this->respectMaxMinLengthRelevant,
            'respectMaxMinLengthValid' => $this->respectMaxMinLengthValid,
            'respectMaxMinLengthSeen' => $this->respectMaxMinLengthSeen,
            'respectMaxMinWidthRelevant' => $this->respectMaxMinWidthRelevant,
            'respectMaxMinWidthValid' => $this->respectMaxMinWidthValid,
            'respectMaxMinWidthSeen' => $this->respectMaxMinWidthSeen,
            'respectwallDistanceRightRelevant' => $this->respectwallDistanceRightRelevant,
            'respectwallDistanceRightValid' => $this->respectwallDistanceRightValid,
            'respectwallDistanceRightSeen' => $this->respectwallDistanceRightSeen,
            'respectwallDistanceLeftRelevant' => $this->respectwallDistanceLeftRelevant,
            'respectwallDistanceLeftValid' => $this->respectwallDistanceLeftValid,
            'respectwallDistanceLeftSeen' => $this->respectwallDistanceLeftSeen,
            'penalty' => $this->penalty,
            'respectFossRelevant' => $this->respectFossRelevant,
            'respectFossValide' => $this->respectFossValide,
            'respectFossSeen' => $this->respectFossSeen,
            'respectFossComment' => $this->respectFossComment,
            'respectOtherCarpetRelevant' => $this->respectOtherCarpetRelevant,
            'respectOtherCarpetValid' => $this->respectOtherCarpetValid,
            'respectOtherCarpetSeen' => $this->respectOtherCarpetSeen,
            'respectOtherCarpetComment' => $this->respectOtherCarpetComment,
            'respectColorRelevant' => $this->respectColorRelevant,
            'respectColorValid' => $this->respectColorValid,
            'respectColorSeen' => $this->respectColorSeen,
            'respectColorComment' => $this->respectColorComment,
            'respectMaterialRelevant' => $this->respectMaterialRelevant,
            'respectMaterialValid' => $this->respectMaterialValid,
            'respectMaterialSeen' => $this->respectMaterialSeen,
            'respectMaterialComment' => $this->respectMaterialComment,
            'respectRemarkRelevant' => $this->respectRemarkRelevant,
            'respectRemarkValid' => $this->respectRemarkValid,
            'respectRemarkSeen' => $this->respectRemarkSeen,
            'respectRemarkComment' => $this->respectRemarkComment,
            'respectVelourRelevant' => $this->respectVelourRelevant,
            'respectVelourValid' => $this->respectVelourValid,
            'respectVelourSeen' => $this->respectVelourSeen,
            'respectVelourComment' => $this->respectVelourComment,
            'wallDistanceTopRelevant' => $this->wallDistanceTopRelevant,
            'wallDistanceTopValid' => $this->wallDistanceTopValid,
            'wallDistanceTopSeen' => $this->wallDistanceTopSeen,
            'wallDistanceBottomRelevant' => $this->wallDistanceBottomRelevant,
            'wallDistanceBottomValid' => $this->wallDistanceBottomValid,
            'wallDistanceBottomSeen' => $this->wallDistanceBottomSeen,
            'orderStatus' => $this->orderStatus,
            'checkingList' => $this->checkingList->getId(),
        ];
    }
}