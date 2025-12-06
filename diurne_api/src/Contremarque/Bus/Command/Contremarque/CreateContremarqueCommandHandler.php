<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Setting\Repository\DiscountRuleRepository;

class CreateContremarqueCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly DiscountRuleRepository $discountRuleRepository
    ) {}

    public function __invoke(CreateContremarqueCommand $command): ContremarqueResponse
    {
        $contremarque = $this->contremarqueRepository->findOneByName($command->getDesignation());

        if ($contremarque instanceof Contremarque) {
            throw new ValidationException(['There is an other project with the same name.']);
        }

        $contremarque = new Contremarque();

        $customer = $this->customerRepository->find((int) $command->getCustomerId());
        $contremarque->setCustomer($customer);
        $projectNumber = $this->contremarqueRepository->getNextProjectNumber();

        $contremarqueObject = $this->contremarqueRepository->findOneByNumber($projectNumber);
        if ($contremarqueObject instanceof Contremarque) {
            throw new ValidationException(['There is an other project with the same number.']);
        }
        $contremarque->setProjectNumber($projectNumber);
        $contremarque->setDesignation($command->getDesignation());
        if (!empty($command->getTargetDate())) {
            $dateTarget = new DateTime($command->getTargetDate());
            $contremarque->setTargetDate($dateTarget);
        }
        $contremarque->setDestinationLocation($command->getDestinationLocation());
        $contremarque->setCommissionOnDeposit($command->getCommissionOnDeposit());
        $contremarque->setCommission((string)$command->getCommission());
        if (!empty($command->getCustomerDiscountId())) {
            $customerDiscount = $this->discountRuleRepository->find((int) $command->getCustomerDiscountId());
            $contremarque->setCustomerDiscount($customerDiscount);
        }
        if ($command->getPrescriberId()) {
            $prescripter = $this->customerRepository->find((int) $command->getPrescriberId());
            $contremarque->setPrescriber($prescripter);
        }
        
        $this->contremarqueRepository->persist($contremarque);
        $this->contremarqueRepository->flush();

        return new ContremarqueResponse(
            $contremarque
        );
    }
}
