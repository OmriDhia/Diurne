<?php

namespace App\Contremarque\Entity;

use DateTimeInterface;
use DateTimeImmutable;
use DateTime;
use App\Contact\Entity\Customer;
use App\Contact\Entity\Customer as Client;
use App\Event\Entity\Event;
use App\Setting\Entity\DiscountRule;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueCustomerConstraint(columns: ['project_number'])]
#[ORM\Index(name: 'idx_contremarque_customer', columns: ['customer_id'])]
class Contremarque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $project_number = null;

    #[ORM\Column(length: 50)]
    private ?string $designation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $destination_location = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $target_date = null;

    #[ORM\ManyToOne(inversedBy: 'contremarques')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne]
    private ?DiscountRule $customerDiscount = null;

    #[ORM\ManyToOne]
    private ?Client $prescriber = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $commission = null;

    #[ORM\Column]
    private ?bool $commission_on_deposit = null;

    #[ORM\Column(nullable: true)]
    private ?int $commercialId = null;

    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'contremarque')]
    private Collection $locations;

    /**
     * @var Collection<int, ContremarqueContact>
     */
    #[ORM\OneToMany(targetEntity: ContremarqueContact::class, mappedBy: 'contremarque', cascade: ['remove'])]
    private Collection $contremarqueContacts;

    /**
     * @var Collection<int, ProjectDi>
     */
    #[ORM\OneToMany(targetEntity: ProjectDi::class, mappedBy: 'contremarque', cascade: ['remove'])]
    private Collection $projectDis;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'contremarque', cascade: ['remove'])]
    private Collection $events;

    /**
     * @var Collection<int, CarpetReference>
     */
    #[ORM\OneToMany(targetEntity: CarpetReference::class, mappedBy: 'contremarque', cascade: ['remove'])]
    private Collection $carpetReferences;

    /**
     * @var Collection<int, Quote>
     */
    #[ORM\OneToMany(targetEntity: Quote::class, mappedBy: 'contremarque', orphanRemoval: true)]
    private Collection $quotes;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->contremarqueContacts = new ArrayCollection();
        $this->projectDis = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable(); // Initialize createdAt with current datetime
        $this->updatedAt = new DateTime(); // Initialize updatedAt with current datetime
        $this->carpetReferences = new ArrayCollection();
        $this->quotes = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectNumber(): ?string
    {
        return $this->project_number;
    }

    public function setProjectNumber(string $project_number): static
    {
        $this->project_number = $project_number;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDestinationLocation(): ?string
    {
        return $this->destination_location;
    }

    public function setDestinationLocation(?string $destination_location): static
    {
        $this->destination_location = $destination_location;

        return $this;
    }

    public function getTargetDate(): ?DateTimeInterface
    {
        return $this->target_date;
    }

    public function setTargetDate(?DateTimeInterface $target_date): static
    {
        $this->target_date = $target_date;

        return $this;
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

    public function getCustomerDiscount(): ?DiscountRule
    {
        return $this->customerDiscount;
    }

    public function setCustomerDiscount(?DiscountRule $customerDiscount): static
    {
        $this->customerDiscount = $customerDiscount;

        return $this;
    }

    public function getPrescriber(): ?Client
    {
        return $this->prescriber;
    }

    public function setPrescriber(?Client $prescriber): static
    {
        $this->prescriber = $prescriber;

        return $this;
    }
    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(?string $commission): self
    {
        $this->commission = $commission;
        return $this;
    }

    public function isCommissionOnDeposit(): ?bool
    {
        return $this->commission_on_deposit;
    }

    public function setCommissionOnDeposit(bool $commission_on_deposit): static
    {
        $this->commission_on_deposit = $commission_on_deposit;

        return $this;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function setCommercialId(?int $commercialId): static
    {
        $this->commercialId = $commercialId;

        return $this;
    }

    public function getCurrentCommercialId(): ?int
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return null;
        }

        // Récupérer le commercial actuel (status = Accepted et to = null)
        foreach ($customer->getContactCommercialHistories() as $history) {
            if ($history->getStatus()->getName() === 'Accepted' && $history->getToDate() === null) {
                return $history->getCommercial()->getId();
            }
        }

        // Si les relations ne sont pas chargées, utiliser les données du tableau commercials
        $customerData = $customer->toArray();
        if (!empty($customerData['contactCommercialHistories'])) {
            foreach ($customerData['contactCommercialHistories'] as $history) {
                if ($history->getStatus()->getName() === 'Accepted' && $history->getToDate() === null) {
                    return $history->getCommercial()->getId();
                }
            }
        }

        // Fallback: utiliser les données du tableau commercials qui sont déjà calculées
        $customerData = $this->getCustomer()->toArray();
        if (!empty($customerData['contactCommercialHistories'])) {
            foreach ($customerData['contactCommercialHistories'] as $commercialData) {
                if ($commercialData->getStatus()->getName() === 'Accepted' && $commercialData->getToDate() === null) {
                    return $commercialData->getCommercial()->getId();
                }
            }
        }

        return null;
    }

    /**
     * @return (DateTimeImmutable|DateTimeInterface|bool|int|mixed|null|string)[]
     *
     * @psalm-return array{contremarque_id: int|null, projectNumber: null|string, designation: null|string, destination_location: null|string, target_date: DateTimeInterface|null, customer: ''|mixed, customerDiscount: ''|mixed, prescriber: ''|mixed, commission: null|string, commission_on_deposit: bool|null, commercials: mixed, createdAt: DateTimeImmutable|null, updatedAt: DateTimeInterface|null}
     */
    public function toArray(): array
    {
        $customerData = $this->getCustomer()->toArray();
        $commercialsData = [];
        
        // Récupérer les données des commerciaux
        if (!empty($contactCommercialHistories = $customerData['contactCommercialHistories'])) {
            foreach ($contactCommercialHistories as $commercial) {
                $commercialsData[] = $commercial->toArray();
            }
            usort($commercialsData, fn($a, $b) => strtotime((string) $b['from']) - strtotime((string) $a['from']));
        }

        return [
            'contremarque_id' => $this->getId(),
            'projectNumber' => $this->getProjectNumber(),
            'designation' => $this->getDesignation(),
            'destination_location' => $this->getDestinationLocation(),
            'target_date' => $this->getTargetDate(),
            'customer' => !empty($this->getCustomer()) ? $this->getCustomer()->toArray() : '',
            'customerDiscount' => !empty($this->getCustomerDiscount()) ? $this->getCustomerDiscount()->toArray() : '',
            'prescriber' => !empty($this->getPrescriber()) ? $this->getPrescriber()->toArray() : '',
            'commission' => $this->getCommission(),
            'commission_on_deposit' => $this->isCommissionOnDeposit(),
            'commercialId' => $this->getCurrentCommercialId(),
            'commercials' => $commercialsData,
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
        ];
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setContremarque($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getContremarque() === $this) {
                $location->setContremarque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContremarqueContact>
     */
    public function getContremarqueContacts(): Collection
    {
        return $this->contremarqueContacts;
    }

    public function addContremarqueContact(ContremarqueContact $contremarqueContact): static
    {
        if (!$this->contremarqueContacts->contains($contremarqueContact)) {
            $this->contremarqueContacts->add($contremarqueContact);
            $contremarqueContact->setContremarque($this);
        }

        return $this;
    }

    public function removeContremarqueContact(ContremarqueContact $contremarqueContact): static
    {
        if ($this->contremarqueContacts->removeElement($contremarqueContact)) {
            // set the owning side to null (unless already changed)
            if ($contremarqueContact->getContremarque() === $this) {
                $contremarqueContact->setContremarque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectDi>
     */
    public function getProjectDis(): Collection
    {
        return $this->projectDis;
    }

    public function addProjectDi(ProjectDi $projectDi): static
    {
        if (!$this->projectDis->contains($projectDi)) {
            $this->projectDis->add($projectDi);
            $projectDi->setContremarque($this);
        }

        return $this;
    }

    public function removeProjectDi(ProjectDi $projectDi): static
    {
        if ($this->projectDis->removeElement($projectDi)) {
            // set the owning side to null (unless already changed)
            if ($projectDi->getContremarque() === $this) {
                $projectDi->setContremarque(null);
            }
        }

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
            $event->setContremarque($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getContremarque() === $this) {
                $event->setContremarque(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, CarpetReference>
     */
    public function getCarpetReferences(): Collection
    {
        return $this->carpetReferences;
    }

    public function addCarpetReference(CarpetReference $carpetReference): static
    {
        if (!$this->carpetReferences->contains($carpetReference)) {
            $this->carpetReferences->add($carpetReference);
            $carpetReference->setContremarque($this);
        }

        return $this;
    }

    public function removeCarpetReference(CarpetReference $carpetReference): static
    {
        if ($this->carpetReferences->removeElement($carpetReference)) {
            // set the owning side to null (unless already changed)
            if ($carpetReference->getContremarque() === $this) {
                $carpetReference->setContremarque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): static
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
            $quote->setContremarque($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): static
    {
        if ($this->quotes->removeElement($quote)) {
            // set the owning side to null (unless already changed)
            if ($quote->getContremarque() === $this) {
                $quote->setContremarque(null);
            }
        }

        return $this;
    }
}
