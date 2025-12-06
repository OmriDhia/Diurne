<?php
declare(strict_types=1);

namespace App\CheckingList\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class QualityCheck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\OneToOne(inversedBy: "qualityCheck")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private CheckingList $checkingList;

    // Graphic Validation
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $graphicRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $graphicValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $graphicSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $graphicComment = null;

    // Instruction Compliance
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $instructionRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $instructionComplianceValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $instructionSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $instructionComment = null;

    // Repair Relevant
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $repairRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $repairRelevantValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $repairSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $repairComment = null;

    // Tightness
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $tightnessRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $tightnessValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $tightnessSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $tightnessComment = null;

    // Wool Quality
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $woolRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $woolQualityValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $woolSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $woolComment = null;

    // Silk Quality
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $silkRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $silkQualityValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $silkSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $silkComment = null;

    // Special Shape
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $specialShapeRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $specialShapeRelevantValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $specialShapeSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $specialShapeComment = null;

    // Corps Ondu Coins
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $corpsOnduCoinsRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $corpsOnduCoinsValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $corpsOnduCoinsSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $corpsOnduCoinsComment = null;

    // Velour Author
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $velourAuthorRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $velourAuthorValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $velourAuthorSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $velourComment = null;

    // Washing
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $washingRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $washingValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $washingSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $wachingComment = null;

    // Cleaning
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $cleaningRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $cleaningValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $cleaningSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $cleaningComment = null;

    // Carving
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $carvingRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $carvingValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $carvingSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $carvingComment = null;

    // Fabric Color
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $fabricColorRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $fabricColorValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $fabricColorSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $fabricColorComment = null;

    // Frange
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $frangeRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $frangeValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $frangeSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $frangComment = null;

    // No Binding
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $noBindingRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $noBindingValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $noBindingSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $noBindingComment = null;

    // Signature
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $signatureRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $signatureValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $signatureSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $signatureComment = null;

    // Without Backing
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $withoutBackingRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $withoutBackingValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $withoutBackingSeen = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $withoutBackingComment = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comment = null;

    // Getters and Setters for Graphic
    public function isGraphicRelevant(): ?bool { return $this->graphicRelevant; }
    public function setGraphicRelevant(?bool $graphicRelevant): void { $this->graphicRelevant = $graphicRelevant; }
    public function isGraphicValidation(): ?bool { return $this->graphicValidation; }
    public function setGraphicValidation(?bool $graphicValidation): void { $this->graphicValidation = $graphicValidation; }
    public function isGraphicSeen(): ?bool { return $this->graphicSeen; }
    public function setGraphicSeen(?bool $graphicSeen): void { $this->graphicSeen = $graphicSeen; }
    public function getGraphicComment(): ?string { return $this->graphicComment; }
    public function setGraphicComment(?string $graphicComment): void { $this->graphicComment = $graphicComment; }

    // Getters and Setters for Instruction
    public function isInstructionRelevant(): ?bool { return $this->instructionRelevant; }
    public function setInstructionRelevant(?bool $instructionRelevant): void { $this->instructionRelevant = $instructionRelevant; }
    public function isInstructionComplianceValidation(): ?bool { return $this->instructionComplianceValidation; }
    public function setInstructionComplianceValidation(?bool $instructionComplianceValidation): void { $this->instructionComplianceValidation = $instructionComplianceValidation; }
    public function isInstructionSeen(): ?bool { return $this->instructionSeen; }
    public function setInstructionSeen(?bool $instructionSeen): void { $this->instructionSeen = $instructionSeen; }
    public function getInstructionComment(): ?string { return $this->instructionComment; }
    public function setInstructionComment(?string $instructionComment): void { $this->instructionComment = $instructionComment; }

    // Getters and Setters for Repair
    public function isRepairRelevant(): ?bool { return $this->repairRelevant; }
    public function setRepairRelevant(?bool $repairRelevant): void { $this->repairRelevant = $repairRelevant; }
    public function isRepairRelevantValidation(): ?bool { return $this->repairRelevantValidation; }
    public function setRepairRelevantValidation(?bool $repairRelevantValidation): void { $this->repairRelevantValidation = $repairRelevantValidation; }
    public function isRepairSeen(): ?bool { return $this->repairSeen; }
    public function setRepairSeen(?bool $repairSeen): void { $this->repairSeen = $repairSeen; }
    public function getRepairComment(): ?string { return $this->repairComment; }
    public function setRepairComment(?string $repairComment): void { $this->repairComment = $repairComment; }

    // Getters and Setters for Tightness
    public function isTightnessRelevant(): ?bool { return $this->tightnessRelevant; }
    public function setTightnessRelevant(?bool $tightnessRelevant): void { $this->tightnessRelevant = $tightnessRelevant; }
    public function isTightnessValidation(): ?bool { return $this->tightnessValidation; }
    public function setTightnessValidation(?bool $tightnessValidation): void { $this->tightnessValidation = $tightnessValidation; }
    public function isTightnessSeen(): ?bool { return $this->tightnessSeen; }
    public function setTightnessSeen(?bool $tightnessSeen): void { $this->tightnessSeen = $tightnessSeen; }
    public function getTightnessComment(): ?string { return $this->tightnessComment; }
    public function setTightnessComment(?string $tightnessComment): void { $this->tightnessComment = $tightnessComment; }

    // Getters and Setters for Wool
    public function isWoolRelevant(): ?bool { return $this->woolRelevant; }
    public function setWoolRelevant(?bool $woolRelevant): void { $this->woolRelevant = $woolRelevant; }
    public function isWoolQualityValidation(): ?bool { return $this->woolQualityValidation; }
    public function setWoolQualityValidation(?bool $woolQualityValidation): void { $this->woolQualityValidation = $woolQualityValidation; }
    public function isWoolSeen(): ?bool { return $this->woolSeen; }
    public function setWoolSeen(?bool $woolSeen): void { $this->woolSeen = $woolSeen; }
    public function getWoolComment(): ?string { return $this->woolComment; }
    public function setWoolComment(?string $woolComment): void { $this->woolComment = $woolComment; }

    // Getters and Setters for Silk
    public function isSilkRelevant(): ?bool { return $this->silkRelevant; }
    public function setSilkRelevant(?bool $silkRelevant): void { $this->silkRelevant = $silkRelevant; }
    public function isSilkQualityValidation(): ?bool { return $this->silkQualityValidation; }
    public function setSilkQualityValidation(?bool $silkQualityValidation): void { $this->silkQualityValidation = $silkQualityValidation; }
    public function isSilkSeen(): ?bool { return $this->silkSeen; }
    public function setSilkSeen(?bool $silkSeen): void { $this->silkSeen = $silkSeen; }
    public function getSilkComment(): ?string { return $this->silkComment; }
    public function setSilkComment(?string $silkComment): void { $this->silkComment = $silkComment; }

    // Getters and Setters for Special Shape
    public function isSpecialShapeRelevant(): ?bool { return $this->specialShapeRelevant; }
    public function setSpecialShapeRelevant(?bool $specialShapeRelevant): void { $this->specialShapeRelevant = $specialShapeRelevant; }
    public function isSpecialShapeRelevantValidation(): ?bool { return $this->specialShapeRelevantValidation; }
    public function setSpecialShapeRelevantValidation(?bool $specialShapeRelevantValidation): void { $this->specialShapeRelevantValidation = $specialShapeRelevantValidation; }
    public function isSpecialShapeSeen(): ?bool { return $this->specialShapeSeen; }
    public function setSpecialShapeSeen(?bool $specialShapeSeen): void { $this->specialShapeSeen = $specialShapeSeen; }
    public function getSpecialShapeComment(): ?string { return $this->specialShapeComment; }
    public function setSpecialShapeComment(?string $specialShapeComment): void { $this->specialShapeComment = $specialShapeComment; }

    // Getters and Setters for Corps Ondu Coins
    public function isCorpsOnduCoinsRelevant(): ?bool { return $this->corpsOnduCoinsRelevant; }
    public function setCorpsOnduCoinsRelevant(?bool $corpsOnduCoinsRelevant): void { $this->corpsOnduCoinsRelevant = $corpsOnduCoinsRelevant; }
    public function isCorpsOnduCoinsValidation(): ?bool { return $this->corpsOnduCoinsValidation; }
    public function setCorpsOnduCoinsValidation(?bool $corpsOnduCoinsValidation): void { $this->corpsOnduCoinsValidation = $corpsOnduCoinsValidation; }
    public function isCorpsOnduCoinsSeen(): ?bool { return $this->corpsOnduCoinsSeen; }
    public function setCorpsOnduCoinsSeen(?bool $corpsOnduCoinsSeen): void { $this->corpsOnduCoinsSeen = $corpsOnduCoinsSeen; }
    public function getCorpsOnduCoinsComment(): ?string { return $this->corpsOnduCoinsComment; }
    public function setCorpsOnduCoinsComment(?string $corpsOnduCoinsComment): void { $this->corpsOnduCoinsComment = $corpsOnduCoinsComment; }

    // Getters and Setters for Velour Author
    public function isVelourAuthorRelevant(): ?bool { return $this->velourAuthorRelevant; }
    public function setVelourAuthorRelevant(?bool $velourAuthorRelevant): void { $this->velourAuthorRelevant = $velourAuthorRelevant; }
    public function isVelourAuthorValidation(): ?bool { return $this->velourAuthorValidation; }
    public function setVelourAuthorValidation(?bool $velourAuthorValidation): void { $this->velourAuthorValidation = $velourAuthorValidation; }
    public function isVelourAuthorSeen(): ?bool { return $this->velourAuthorSeen; }
    public function setVelourAuthorSeen(?bool $velourAuthorSeen): void { $this->velourAuthorSeen = $velourAuthorSeen; }
    public function getVelourComment(): ?string { return $this->velourComment; }
    public function setVelourComment(?string $velourComment): void { $this->velourComment = $velourComment; }

    // Getters and Setters for Washing
    public function isWashingRelevant(): ?bool { return $this->washingRelevant; }
    public function setWashingRelevant(?bool $washingRelevant): void { $this->washingRelevant = $washingRelevant; }
    public function isWashingValidation(): ?bool { return $this->washingValidation; }
    public function setWashingValidation(?bool $washingValidation): void { $this->washingValidation = $washingValidation; }
    public function isWashingSeen(): ?bool { return $this->washingSeen; }
    public function setWashingSeen(?bool $washingSeen): void { $this->washingSeen = $washingSeen; }
    public function getWachingComment(): ?string { return $this->wachingComment; }
    public function setWachingComment(?string $wachingComment): void { $this->wachingComment = $wachingComment; }

    // Getters and Setters for Cleaning
    public function isCleaningRelevant(): ?bool { return $this->cleaningRelevant; }
    public function setCleaningRelevant(?bool $cleaningRelevant): void { $this->cleaningRelevant = $cleaningRelevant; }
    public function isCleaningValidation(): ?bool { return $this->cleaningValidation; }
    public function setCleaningValidation(?bool $cleaningValidation): void { $this->cleaningValidation = $cleaningValidation; }
    public function isCleaningSeen(): ?bool { return $this->cleaningSeen; }
    public function setCleaningSeen(?bool $cleaningSeen): void { $this->cleaningSeen = $cleaningSeen; }
    public function getCleaningComment(): ?string { return $this->cleaningComment; }
    public function setCleaningComment(?string $cleaningComment): void { $this->cleaningComment = $cleaningComment; }

    // Getters and Setters for Carving
    public function isCarvingRelevant(): ?bool { return $this->carvingRelevant; }
    public function setCarvingRelevant(?bool $carvingRelevant): void { $this->carvingRelevant = $carvingRelevant; }
    public function isCarvingValidation(): ?bool { return $this->carvingValidation; }
    public function setCarvingValidation(?bool $carvingValidation): void { $this->carvingValidation = $carvingValidation; }
    public function isCarvingSeen(): ?bool { return $this->carvingSeen; }
    public function setCarvingSeen(?bool $carvingSeen): void { $this->carvingSeen = $carvingSeen; }
    public function getCarvingComment(): ?string { return $this->carvingComment; }
    public function setCarvingComment(?string $carvingComment): void { $this->carvingComment = $carvingComment; }

    // Getters and Setters for Fabric Color
    public function isFabricColorRelevant(): ?bool { return $this->fabricColorRelevant; }
    public function setFabricColorRelevant(?bool $fabricColorRelevant): void { $this->fabricColorRelevant = $fabricColorRelevant; }
    public function isFabricColorValidation(): ?bool { return $this->fabricColorValidation; }
    public function setFabricColorValidation(?bool $fabricColorValidation): void { $this->fabricColorValidation = $fabricColorValidation; }
    public function isFabricColorSeen(): ?bool { return $this->fabricColorSeen; }
    public function setFabricColorSeen(?bool $fabricColorSeen): void { $this->fabricColorSeen = $fabricColorSeen; }
    public function getFabricColorComment(): ?string { return $this->fabricColorComment; }
    public function setFabricColorComment(?string $fabricColorComment): void { $this->fabricColorComment = $fabricColorComment; }

    // Getters and Setters for Frange
    public function isFrangeRelevant(): ?bool { return $this->frangeRelevant; }
    public function setFrangeRelevant(?bool $frangeRelevant): void { $this->frangeRelevant = $frangeRelevant; }
    public function isFrangeValidation(): ?bool { return $this->frangeValidation; }
    public function setFrangeValidation(?bool $frangeValidation): void { $this->frangeValidation = $frangeValidation; }
    public function isFrangeSeen(): ?bool { return $this->frangeSeen; }
    public function setFrangeSeen(?bool $frangeSeen): void { $this->frangeSeen = $frangeSeen; }
    public function getFrangComment(): ?string { return $this->frangComment; }
    public function setFrangComment(?string $frangComment): void { $this->frangComment = $frangComment; }

    // Getters and Setters for No Binding
    public function isNoBindingRelevant(): ?bool { return $this->noBindingRelevant; }
    public function setNoBindingRelevant(?bool $noBindingRelevant): void { $this->noBindingRelevant = $noBindingRelevant; }
    public function isNoBindingValidation(): ?bool { return $this->noBindingValidation; }
    public function setNoBindingValidation(?bool $noBindingValidation): void { $this->noBindingValidation = $noBindingValidation; }
    public function isNoBindingSeen(): ?bool { return $this->noBindingSeen; }
    public function setNoBindingSeen(?bool $noBindingSeen): void { $this->noBindingSeen = $noBindingSeen; }
    public function getNoBindingComment(): ?string { return $this->noBindingComment; }
    public function setNoBindingComment(?string $noBindingComment): void { $this->noBindingComment = $noBindingComment; }

    // Getters and Setters for Signature
    public function isSignatureRelevant(): ?bool { return $this->signatureRelevant; }
    public function setSignatureRelevant(?bool $signatureRelevant): void { $this->signatureRelevant = $signatureRelevant; }
    public function isSignatureValidation(): ?bool { return $this->signatureValidation; }
    public function setSignatureValidation(?bool $signatureValidation): void { $this->signatureValidation = $signatureValidation; }
    public function isSignatureSeen(): ?bool { return $this->signatureSeen; }
    public function setSignatureSeen(?bool $signatureSeen): void { $this->signatureSeen = $signatureSeen; }
    public function getSignatureComment(): ?string { return $this->signatureComment; }
    public function setSignatureComment(?string $signatureComment): void { $this->signatureComment = $signatureComment; }

    // Getters and Setters for Without Backing
    public function isWithoutBackingRelevant(): ?bool { return $this->withoutBackingRelevant; }
    public function setWithoutBackingRelevant(?bool $withoutBackingRelevant): void { $this->withoutBackingRelevant = $withoutBackingRelevant; }
    public function isWithoutBackingValidation(): ?bool { return $this->withoutBackingValidation; }
    public function setWithoutBackingValidation(?bool $withoutBackingValidation): void { $this->withoutBackingValidation = $withoutBackingValidation; }
    public function isWithoutBackingSeen(): ?bool { return $this->withoutBackingSeen; }
    public function setWithoutBackingSeen(?bool $withoutBackingSeen): void { $this->withoutBackingSeen = $withoutBackingSeen; }
    public function getWithoutBackingComment(): ?string { return $this->withoutBackingComment; }
    public function setWithoutBackingComment(?string $withoutBackingComment): void { $this->withoutBackingComment = $withoutBackingComment; }

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'graphicRelevant' => $this->graphicRelevant,
            'graphicValidation' => $this->graphicValidation,
            'graphicSeen' => $this->graphicSeen,
            'graphicComment' => $this->graphicComment,
            'instructionRelevant' => $this->instructionRelevant,
            'instructionComplianceValidation' => $this->instructionComplianceValidation,
            'instructionSeen' => $this->instructionSeen,
            'instructionComment' => $this->instructionComment,
            'repairRelevant' => $this->repairRelevant,
            'repairRelevantValidation' => $this->repairRelevantValidation,
            'repairSeen' => $this->repairSeen,
            'repairComment' => $this->repairComment,
            'tightnessRelevant' => $this->tightnessRelevant,
            'tightnessValidation' => $this->tightnessValidation,
            'tightnessSeen' => $this->tightnessSeen,
            'tightnessComment' => $this->tightnessComment,
            'woolRelevant' => $this->woolRelevant,
            'woolQualityValidation' => $this->woolQualityValidation,
            'woolSeen' => $this->woolSeen,
            'woolComment' => $this->woolComment,
            'silkRelevant' => $this->silkRelevant,
            'silkQualityValidation' => $this->silkQualityValidation,
            'silkSeen' => $this->silkSeen,
            'silkComment' => $this->silkComment,
            'specialShapeRelevant' => $this->specialShapeRelevant,
            'specialShapeRelevantValidation' => $this->specialShapeRelevantValidation,
            'specialShapeSeen' => $this->specialShapeSeen,
            'specialShapeComment' => $this->specialShapeComment,
            'corpsOnduCoinsRelevant' => $this->corpsOnduCoinsRelevant,
            'corpsOnduCoinsValidation' => $this->corpsOnduCoinsValidation,
            'corpsOnduCoinsSeen' => $this->corpsOnduCoinsSeen,
            'corpsOnduCoinsComment' => $this->corpsOnduCoinsComment,
            'velourAuthorRelevant' => $this->velourAuthorRelevant,
            'velourAuthorValidation' => $this->velourAuthorValidation,
            'velourAuthorSeen' => $this->velourAuthorSeen,
            'velourComment' => $this->velourComment,
            'washingRelevant' => $this->washingRelevant,
            'washingValidation' => $this->washingValidation,
            'washingSeen' => $this->washingSeen,
            'wachingComment' => $this->wachingComment,
            'cleaningRelevant' => $this->cleaningRelevant,
            'cleaningValidation' => $this->cleaningValidation,
            'cleaningSeen' => $this->cleaningSeen,
            'cleaningComment' => $this->cleaningComment,
            'carvingRelevant' => $this->carvingRelevant,
            'carvingValidation' => $this->carvingValidation,
            'carvingSeen' => $this->carvingSeen,
            'carvingComment' => $this->carvingComment,
            'fabricColorRelevant' => $this->fabricColorRelevant,
            'fabricColorValidation' => $this->fabricColorValidation,
            'fabricColorSeen' => $this->fabricColorSeen,
            'fabricColorComment' => $this->fabricColorComment,
            'frangeRelevant' => $this->frangeRelevant,
            'frangeValidation' => $this->frangeValidation,
            'frangeSeen' => $this->frangeSeen,
            'frangComment' => $this->frangComment,
            'noBindingRelevant' => $this->noBindingRelevant,
            'noBindingValidation' => $this->noBindingValidation,
            'noBindingSeen' => $this->noBindingSeen,
            'noBindingComment' => $this->noBindingComment,
            'signatureRelevant' => $this->signatureRelevant,
            'signatureValidation' => $this->signatureValidation,
            'signatureSeen' => $this->signatureSeen,
            'signatureComment' => $this->signatureComment,
            'withoutBackingRelevant' => $this->withoutBackingRelevant,
            'withoutBackingValidation' => $this->withoutBackingValidation,
            'withoutBackingSeen' => $this->withoutBackingSeen,
            'withoutBackingComment' => $this->withoutBackingComment,
            'comment' => $this->comment,
            'checkingList' => $this->checkingList->getId(),
        ];
    }
}