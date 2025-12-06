<?php

namespace App\Setting\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TarifGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $year = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Convert the entity to an array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'year' => $this->year,
        ];
    }
}
