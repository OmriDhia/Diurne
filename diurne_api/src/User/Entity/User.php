<?php

namespace App\User\Entity;

use App\Contremarque\Entity\ImageCommand\ImageCommandDesignerAssignment;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Profile $profile = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Gender $gender = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'designer', targetEntity: ImageCommandDesignerAssignment::class)]
    private Collection $assignments;
    public function __construct()
    {
        $this->assignments = new ArrayCollection();
    }
    public static function createDummyUser(
        string   $email,
        string   $password,
        string   $firstname,
        string   $lastname,
        array    $roles,
        ?Profile $profile = null,
        ?Gender  $gender = null,
        bool     $isActive = true
    ): self {
        $user = new self();
        $user->setEmail($email)
            ->setPassword(password_hash($password, PASSWORD_BCRYPT))
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setRoles($roles)
            ->setProfile($profile)
            ->setGender($gender)
            ->setIsActive($isActive);

        return $user;
    }

    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(ImageCommandDesignerAssignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments->add($assignment);
            $assignment->setDesigner($this);
        }
        return $this;
    }

    public function removeAssignment(ImageCommandDesignerAssignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            if ($assignment->getDesigner() === $this) {
                $assignment->setDesigner(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     *
     * @return null|string
     */
    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'gender' => $this->getGender() ? $this->getGender()->getName() : '',
            'email' => $this->email,
            'roles' => $this->roles,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'is_active' => $this->isActive,
        ];

        $profile = $this->getProfile();
        if ($profile) {
            $data['profile'] = $profile->toArray(); // Assuming Profile has a toArray method as well
        } else {
            $data['profile'] = null;
        }

        return $data;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function addDummyRole(): static
    {
        if (!in_array('ROLE_DUMMY', $this->roles, true)) {
            $this->roles[] = 'ROLE_DUMMY';
        }

        return $this;
    }

    public function isDummyUser(): bool
    {
        return in_array('ROLE_DUMMY', $this->roles, true);
    }
}
