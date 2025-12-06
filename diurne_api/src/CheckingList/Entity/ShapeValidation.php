<?php
declare(strict_types=1);

namespace App\CheckingList\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ShapeValidation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\OneToOne(inversedBy: "shapeValidation")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private CheckingList $checkingList;

    // Shape Validation
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $shapeRelevant = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $shapeValidation = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $shapeSeen = null;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6)]
    private string $realWidth;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6)]
    private string $realLength;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6)]
    private string $surface;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, nullable: true)]
    private ?string $diagonalA = null;

    #[ORM\Column(type: "decimal", precision: 20, scale: 6, nullable: true)]
    private ?string $diagonalB = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comment = null;

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

    // Getters and Setters for Shape
    public function isShapeRelevant(): ?bool { return $this->shapeRelevant; }
    public function setShapeRelevant(?bool $shapeRelevant): void { $this->shapeRelevant = $shapeRelevant; }
    public function isShapeValidation(): ?bool { return $this->shapeValidation; }
    public function setShapeValidation(?bool $shapeValidation): void { $this->shapeValidation = $shapeValidation; }
    public function isShapeSeen(): ?bool { return $this->shapeSeen; }
    public function setShapeSeen(?bool $shapeSeen): void { $this->shapeSeen = $shapeSeen; }

    public function getRealWidth(): string
    {
        return $this->realWidth;
    }

    public function setRealWidth(string $realWidth): void
    {
        $this->realWidth = $realWidth;
    }

    public function getRealLength(): string
    {
        return $this->realLength;
    }

    public function setRealLength(string $realLength): void
    {
        $this->realLength = $realLength;
    }

    public function getSurface(): string
    {
        return $this->surface;
    }

    public function setSurface(string $surface): void
    {
        $this->surface = $surface;
    }

    public function getDiagonalA(): ?string
    {
        return $this->diagonalA;
    }

    public function setDiagonalA(?string $diagonalA): void
    {
        $this->diagonalA = $diagonalA;
    }

    public function getDiagonalB(): ?string
    {
        return $this->diagonalB;
    }

    public function setDiagonalB(?string $diagonalB): void
    {
        $this->diagonalB = $diagonalB;
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
            'id' => $this->id,
            'shapeRelevant' => $this->shapeRelevant,
            'shapeValidation' => $this->shapeValidation,
            'shapeSeen' => $this->shapeSeen,
            'realWidth' => $this->realWidth,
            'realLength' => $this->realLength,
            'surface' => $this->surface,
            'diagonalA' => $this->diagonalA,
            'diagonalB' => $this->diagonalB,
            'comment' => $this->comment,
            'checkingList' => $this->checkingList->getId(),
        ];
    }
}
