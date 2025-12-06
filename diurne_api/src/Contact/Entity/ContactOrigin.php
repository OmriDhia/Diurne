<?php

namespace App\Contact\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contact_origin')]
class ContactOrigin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(length: 100, unique: true)]
    private string $label;

    public function toArray(): array
    {
        return [
            'contact_origin_id' => $this->getId(),
            'label' => $this->getLabel(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}