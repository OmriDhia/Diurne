<?php

namespace App\Contact\Entity;

use DateTimeImmutable;
use App\Contremarque\Entity\Contremarque;
use App\Event\Entity\Event;
use App\Setting\Entity\DiscountRule;
use App\Setting\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $socialReason = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $tva_ce = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?CustomerGroup $customerGroup = null;

    #[ORM\ManyToMany(targetEntity: Address::class, inversedBy: 'customers')]
    private Collection $addresses;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?DiscountRule $discountRule = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'customer')]
    private Collection $contact;

    #[ORM\OneToMany(targetEntity: ContactCommercialHistory::class, mappedBy: 'customer')]
    private Collection $contactCommercialHistories;

    #[ORM\OneToMany(targetEntity: CustomerIntermediaryHistory::class, mappedBy: 'customer')]
    private Collection $customerIntermediaryHistories;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'customer')]
    private Collection $events;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deletedBy = null;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Language $mailingLanguage = null;

    #[ORM\OneToMany(targetEntity: Contremarque::class, mappedBy: 'customer')]
    private Collection $contremarques;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ContactInformationSheet $contactInformationSheet = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isIntermediary = false;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?IntermediaryInformationSheet $intermediaryInformationSheet = null;

    #[ORM\ManyToOne(targetEntity: ContactOrigin::class)]
    #[ORM\JoinColumn(name: 'contact_origin_id', referencedColumnName: 'id', nullable: false)]
    private ?ContactOrigin $contactOrigin = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->contact = new ArrayCollection();
        $this->contactCommercialHistories = new ArrayCollection();
        $this->customerIntermediaryHistories = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->contremarques = new ArrayCollection();
        $this->isIntermediary = false;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

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

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contact->contains($contact)) {
            $this->contact->add($contact);
            $contact->setCustomer($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contact->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCustomer() === $this) {
                $contact->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContactCommercialHistory>
     */
    public function getContactCommercialHistories(): Collection
    {
        return $this->contactCommercialHistories;
    }

    public function addContactCommercialHistory(ContactCommercialHistory $contactCommercialHistory): static
    {
        if (!$this->contactCommercialHistories->contains($contactCommercialHistory)) {
            $this->contactCommercialHistories->add($contactCommercialHistory);
            $contactCommercialHistory->setCustomer($this);
        }

        return $this;
    }

    public function removeContactCommercialHistory(ContactCommercialHistory $contactCommercialHistory): static
    {
        if ($this->contactCommercialHistories->removeElement($contactCommercialHistory)) {
            // set the owning side to null (unless already changed)
            if ($contactCommercialHistory->getCustomer() === $this) {
                $contactCommercialHistory->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomerIntermediaryHistory>
     */
    public function getCustomerIntermediaryHistories(): Collection
    {
        return $this->customerIntermediaryHistories;
    }

    public function addCustomerIntermediaryHistory(CustomerIntermediaryHistory $customerIntermediaryHistory): static
    {
        if (!$this->customerIntermediaryHistories->contains($customerIntermediaryHistory)) {
            $this->customerIntermediaryHistories->add($customerIntermediaryHistory);
            $customerIntermediaryHistory->setCustomer($this);
        }

        return $this;
    }

    /**
     * @return (Collection|array|bool|int|mixed|null|string)[]
     *
     * @psalm-return array{customer_id: int|null, code: null|string, socialReason: null|string, customerName: string, tva_ce: null|string, website: null|string, active: bool|null, customerGroup: array<never, never>|mixed, discountRule: array<never, never>|mixed, created_at: string, updated_at: string, addresses: Collection, contacts: Collection, contactCommercialHistories: Collection, customerIntermediaryHistories: Collection, current_commercial: null|string, mailingLanguage: array<never, never>|int|null, contact_origin_label: null|string, commentaire: null|string}
     */
    public function toArray(): array
    {
        $latestValidatedCommercialHistory = null;

        // Loop through contactCommercialHistories and find the one with latest toDate
        foreach ($this->contactCommercialHistories as $history) {
            if ('Accepted' === $history->getStatus()) {
                if (null === $latestValidatedCommercialHistory || $history->getToDate() > $latestValidatedCommercialHistory->getToDate()) {
                    $latestValidatedCommercialHistory = $history;
                }
            }
        }

        $commercialData = null;
        if ($latestValidatedCommercialHistory) {
            $commercialData = $latestValidatedCommercialHistory->getCommercial()->getFirstname() . ' ' . $latestValidatedCommercialHistory->getCommercial()->getLastname();
        }
        if ('Particulier (Client)' == $this->getCustomerGroup()->getName()) {
            $firstName = $this->getContactInformationSheet()->getFirstname();
            $lastName = $this->getContactInformationSheet()->getLastname();
        } else {
            $firstName = $this->getSocialReason();
            $lastName = '(' . $this->getCode() . ')';
        }

        return [
            'customer_id' => $this->getId(),
            'code' => $this->getCode(),
            'socialReason' => $this->getSocialReason(),
            'customerName' => $firstName . ' ' . $lastName,
            'tva_ce' => $this->getTvaCe(),
            'website' => $this->getWebsite(),
            'active' => $this->isActive(),
            'customerGroup' => !empty($this->getCustomerGroup()) ? $this->getCustomerGroup()->toArray() : [],
            'discountRule' => !empty($this->getDiscountRule()) ? $this->getDiscountRule()->toArray() : [],
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'addresses' => $this->addresses,
            'contacts' => $this->contact,
            'contactCommercialHistories' => $this->contactCommercialHistories,
            'customerIntermediaryHistories' => $this->customerIntermediaryHistories,
            'current_commercial' => $commercialData,
            'mailingLanguage' => !empty($this->getMailingLanguage()) ? $this->getMailingLanguage()->getId() : [],
            'contact_origin_label' => $this->getContactOrigin()?->getLabel(),
            'commentaire' => $this->getCommentaire(),
        ];
    }

    public function getCustomerGroup(): ?CustomerGroup
    {
        return $this->customerGroup;
    }

    public function setCustomerGroup(?CustomerGroup $customerGroup): static
    {
        $this->customerGroup = $customerGroup;

        return $this;
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

    public function getSocialReason(): ?string
    {
        return $this->socialReason;
    }

    public function setSocialReason(?string $socialReason): static
    {
        $this->socialReason = $socialReason;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTvaCe(): ?string
    {
        return $this->tva_ce;
    }

    public function setTvaCe(?string $tva_ce): static
    {
        $this->tva_ce = $tva_ce;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getDiscountRule(): ?DiscountRule
    {
        return $this->discountRule;
    }

    public function setDiscountRule(?DiscountRule $discountRule): static
    {
        $this->discountRule = $discountRule;

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

    public function getMailingLanguage(): ?Language
    {
        return $this->mailingLanguage;
    }

    //    public function removeCustomerIntermediaryHistory(CustomerIntermediaryHistory $customerIntermediaryHistory): static
    //    {
    //        if ($this->customerIntermediaryHistories->removeElement($customerIntermediaryHistory)) {
    //            // set the owning side to null (unless already changed)
    //            if ($customerIntermediaryHistory->getCustomer() === $this) {
    //                $customerIntermediaryHistory->setCustomer(null);
    //            }
    //        }
    //    }

    public function setMailingLanguage(?Language $mailingLanguage): static
    {
        $this->mailingLanguage = $mailingLanguage;

        return $this;
    }

    public function getContactOrigin(): ?ContactOrigin
    {
        return $this->contactOrigin;
    }

    public function setContactOrigin(?ContactOrigin $contactOrigin): static
    {
        $this->contactOrigin = $contactOrigin;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCustomer($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCustomer() === $this) {
                $event->setCustomer(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): static
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * @return Collection<int, Contremarque>
     */
    public function getContremarques(): Collection
    {
        return $this->contremarques;
    }

    public function addContremarque(Contremarque $contremarque): static
    {
        if (!$this->contremarques->contains($contremarque)) {
            $this->contremarques->add($contremarque);
            $contremarque->setCustomer($this);
        }

        return $this;
    }

    public function removeContremarque(Contremarque $contremarque): static
    {
        if ($this->contremarques->removeElement($contremarque)) {
            // set the owning side to null (unless already changed)
            if ($contremarque->getCustomer() === $this) {
                $contremarque->setCustomer(null);
            }
        }

        return $this;
    }

    public function isIntermediary(): ?bool
    {
        return $this->isIntermediary;
    }

    public function setIntermediary(bool $isIntermediary): static
    {
        $this->isIntermediary = $isIntermediary;

        return $this;
    }

    public function getIntermediaryInformationSheet(): ?IntermediaryInformationSheet
    {
        return $this->intermediaryInformationSheet;
    }

    public function setIntermediaryInformationSheet(?IntermediaryInformationSheet $intermediaryInformationSheet): static
    {
        $this->intermediaryInformationSheet = $intermediaryInformationSheet;

        return $this;
    }
}
