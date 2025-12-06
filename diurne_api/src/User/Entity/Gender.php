<?php

namespace App\User\Entity;

use App\Contact\Entity\ContactInformationSheet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Gender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'gender')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: ContactInformationSheet::class, mappedBy: 'gender')]
    private Collection $contactInformationSheets;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->contactInformationSheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGender($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGender() === $this) {
                $user->setGender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContactInformationSheet>
     */
    public function getContactInformationSheets(): Collection
    {
        return $this->contactInformationSheets;
    }

    public function addContactInformationSheet(ContactInformationSheet $contactInformationSheet): static
    {
        if (!$this->contactInformationSheets->contains($contactInformationSheet)) {
            $this->contactInformationSheets->add($contactInformationSheet);
            $contactInformationSheet->setGender($this);
        }

        return $this;
    }

    public function removeContactInformationSheet(ContactInformationSheet $contactInformationSheet): static
    {
        if ($this->contactInformationSheets->removeElement($contactInformationSheet)) {
            // set the owning side to null (unless already changed)
            if ($contactInformationSheet->getGender() === $this) {
                $contactInformationSheet->setGender(null);
            }
        }

        return $this;
    }
}
