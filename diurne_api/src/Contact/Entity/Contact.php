<?php

namespace App\Contact\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContactInformationSheet $contactInformationSheet = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mailing = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mailing_with_caligraphie = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'contact')]
    private ?Customer $customer = null;


    public function setMailing(?bool $mailing): static
    {
        $this->mailing = $mailing;

        return $this;
    }

    public function setMailingWithCaligraphie(?bool $mailing_with_caligraphie): static
    {
        $this->mailing_with_caligraphie = $mailing_with_caligraphie;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return (bool|int|null|string)[]
     *
     * @psalm-return array{contact_id: int|null, mailing: bool|null, mailing_with_caligraphie: bool|null, firstname: null|string, lastname: null|string, email: null|string, phone: null|string, mobile_phone: null|string, fax: null|string, gender_id: int|null, user_id: int|null, created_at: string, updated_at: string}
     */
    public function toArray(): array
    {
        return [
            'contact_id' => $this->getId(),
            'mailing' => $this->isMailing(),
            'mailing_with_caligraphie' => $this->isMailingWithCaligraphie(),
            'firstname' => $this->getContactInformationSheet()->getFirstname(),
            'lastname' => $this->getContactInformationSheet()->getLastname(),
            'email' => $this->getContactInformationSheet()->getEmail(),
            'phone' => $this->getContactInformationSheet()->getPhone(),
            'mobile_phone' => $this->getContactInformationSheet()->getMobilePhone(),
            'fax' => $this->getContactInformationSheet()->getFax(),
            'gender_id' => !empty($this->getContactInformationSheet()->getGender()) ? $this->getContactInformationSheet()->getGender()->getId() : 0,
            'user_id' => $this->getContactInformationSheet()->getUser()->getId(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMailing(): ?bool
    {
        return $this->mailing;
    }

    public function isMailingWithCaligraphie(): ?bool
    {
        return $this->mailing_with_caligraphie;
    }

    public function getContactInformationSheet(): ?ContactInformationSheet
    {
        return $this->contactInformationSheet;
    }

    public function setContactInformationSheet(?ContactInformationSheet $contactInformationSheet): static
    {
        $this->contactInformationSheet = $contactInformationSheet;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new DateTimeImmutable();
    }


}
