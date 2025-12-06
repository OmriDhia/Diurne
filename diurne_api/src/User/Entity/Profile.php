<?php

namespace App\User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[UniqueEntity('name')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 60)]
    private ?string $name = null;

    // Correct the type in your entity

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $discount;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'profile')]
    private Collection $users;
    #[ORM\ManyToMany(targetEntity: Permission::class, cascade: ['persist'])]
    private Collection $permission;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permission = new ArrayCollection();
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

    public function getDiscount(): string
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): static
    {
        $this->discount = $discount;

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
            $user->setProfile($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfile() === $this) {
                $user->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermission(): Collection
    {
        return $this->permission;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permission->contains($permission)) {
            $this->permission->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permission->removeElement($permission);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount' => $this->discount,
            // Exclude collections for simplicity, but you can include them if needed
            // 'users' => $this->users->toArray(),
            // 'permissions' => $this->permission->toArray(),
        ];
    }
}
