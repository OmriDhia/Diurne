<?php

namespace App\Contremarque\Entity;

use App\Contact\Entity\Contact;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(name: 'idx_contremarque_contact', columns: ['contremarque_id', 'current'])]
class ContremarqueContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $current = false;

    #[ORM\ManyToOne(inversedBy: 'contremarqueContacts')]
    private ?Contremarque $contremarque = null;

    #[ORM\ManyToOne]
    private ?Contact $contact = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCurrent(): ?bool
    {
        return $this->current;
    }

    public function setCurrent(bool $current): static
    {
        $this->current = $current;

        return $this;
    }

    public function getContremarque(): ?Contremarque
    {
        return $this->contremarque;
    }

    public function setContremarque(?Contremarque $contremarque): static
    {
        $this->contremarque = $contremarque;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }
}
