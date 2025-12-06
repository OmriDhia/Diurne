<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateCustomerInstruction;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CustomerInstruction;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\SampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCustomerInstructionCommandHandler implements CommandHandler
{
    public function __construct(private readonly SampleRepository $sampleRepository, private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository, private readonly CustomerInstructionRepository $customerInstructionRepository, private readonly EntityManagerInterface $entityManager) {}

    public function __invoke(CreateCustomerInstructionCommand $command): CreateCustomerInstructionResponse
    {
        $customerInstruction = new CustomerInstruction();

        // Fetch the CarpetDesignOrder entity by ID
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getCarpetDesignOrderId());

        if ($carpetDesignOrder && $command->getObjectType() === 'CarpetDesignOrder') {
            $customerInstruction->setCarpetDesignOrder($carpetDesignOrder);
        }

        if (null !== $command->getCustomerInsValidationsDate()) {
            $customerValidationDate = new DateTimeImmutable($command->getCustomerInsValidationsDate());
            $customerInstruction->setCustomerValidationDate($customerValidationDate);
        }
        // Create and populate the CustomerInstruction entity
        $customerInstruction->setOrderNumber($command->getOrderNumber())
            ->setTransmiAdv($command->getTransmiAdv())
            ->setCustomerComment($command->getCustomerComment())
            ->setHasCustomerConstraints(false) // Default value
            ->setHasValidateSample(false) // Default value
            ->setHasFinitionInstruction(false); // Default value

        $this->entityManager->persist($customerInstruction);
        $this->entityManager->flush();

        return new CreateCustomerInstructionResponse($customerInstruction);
    }
}
