<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\IntermediaryInformationSheet;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Setting\Repository\DiscountRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateContremarqueCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly DiscountRuleRepository $discountRuleRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly IntermediaryTypeRepository $intermediaryTypeRepository
    ) {}

    public function __invoke(UpdateContremarqueCommand $command): ContremarqueResponse
    {
        $contremarque = $this->contremarqueRepository->find($command->getId());

        if (!$contremarque instanceof Contremarque) {
            throw new ResourceNotFoundException(['Contremarque not found.']);
        }

        if (null !== $command->getDesignation()) {
            $contremarque->setDesignation($command->getDesignation());
        }

        if (null !== $command->getTargetDate()) {
            $dateTarget = new DateTimeImmutable($command->getTargetDate());
            $contremarque->setTargetDate($dateTarget);
        }

        if (null !== $command->getDestinationLocation()) {
            $contremarque->setDestinationLocation($command->getDestinationLocation());
        }

        if (null !== $command->getCommissionOnDeposit()) {
            $contremarque->setCommissionOnDeposit($command->getCommissionOnDeposit());
        }

        if (null !== $command->getCommission()) {
            $contremarque->setCommission((string)$command->getCommission());
        }

        if (null !== $command->getCustomerId()) {
            $customer = $this->customerRepository->find($command->getCustomerId());
            if (!$customer) {
                throw new ResourceNotFoundException(['Customer not found.']);
            }
            $contremarque->setCustomer($customer);
        }

        if (null !== $command->getCustomerDiscountId()) {
            $customerDiscount = $this->discountRuleRepository->find($command->getCustomerDiscountId());
            if (!$customerDiscount) {
                throw new ResourceNotFoundException(['Customer discount not found.']);
            }
            $contremarque->setCustomerDiscount($customerDiscount);
        }

        if (null !== $command->getPrescriberId()) {
            $prescriber = $this->customerRepository->find($command->getPrescriberId());
            if (!$prescriber) {
                throw new ResourceNotFoundException(['Prescriber not found.']);
            }
            $prescriber->setIntermediary(true);
            $intermediaryInformationSheet = new IntermediaryInformationSheet();
            $intermediaryType = $this->intermediaryTypeRepository->findOneBy(['name' => 'Prescripteur']);
            $intermediaryInformationSheet->setIntermediaryType($intermediaryType);
            $this->entityManager->persist($intermediaryInformationSheet);
            $prescriber->setIntermediaryInformationSheet($intermediaryInformationSheet);
            $this->entityManager->persist($prescriber);
            $this->entityManager->flush();
            $contremarque->setPrescriber($prescriber);
        }

        $this->contremarqueRepository->persist($contremarque);
        $this->contremarqueRepository->flush();

        return new ContremarqueResponse($contremarque);
    }
}
