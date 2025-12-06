<?php

namespace App\Contremarque\Command;

use DateTime;
use Throwable;
use App\Contremarque\Entity\CustomerConstraint;
use App\Contremarque\Entity\CustomerInstruction;
use App\Contremarque\Entity\Finishing;
use App\Contremarque\Entity\ValidatedSample;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CustomerInstructionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:customer-instructions-fixtures',
    description: 'Create customer instructions fixtures'
)]
class CreateCustomerInstructionCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerInstructionRepository $customerInstructionRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Set unlimited memory and execution time to handle large data sets
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        // Retrieve all CustomerInstruction entities
        $customerInstructions = $this->customerInstructionRepository->findAll();

        if (!empty($customerInstructions)) {
            // Loop through each CustomerInstruction entity and remove it
            foreach ($customerInstructions as $index => $customerInstruction) {
                $customerInstruction->setCarpetDesignOrder(null);
                $this->entityManager->persist($customerInstruction);
                $this->entityManager->remove($customerInstruction);

                // Flush every 100 items to avoid memory overflow and clear the entities from memory
                if (0 === $index % 100) {
                    $this->entityManager->flush();
                    $this->entityManager->clear(); // Detach all entities from memory to free up space
                }
            }
        }

        // Retrieve CarpetDesignOrders
        $carpetDesignOrders = $this->carpetDesignOrderRepository->findAll();

        // Date de départ
        $startDate = new DateTime('2024-01-01');

        if (!empty($carpetDesignOrders)) {
            foreach ($carpetDesignOrders as $index => $carpetDesignOrder) {
                try {
                    if (!$carpetDesignOrder->getProjectDi()) {
                        $io->warning('Skipping CarpetDesignOrder with missing ProjectDi: ' . $carpetDesignOrder->getId());
                        continue;
                    }
                    // Create new CustomerInstruction entity
                    $customerInstruction = new CustomerInstruction();
                    $customerInstruction->setOrderNumber($carpetDesignOrder->getId());
                    $customerInstruction->setHasCustomerConstraints(true);
                    $customerInstruction->setHasValidateSample(true);
                    $customerInstruction->setHasFinitionInstruction(true);
                    $currentDate = clone $startDate;
                    $currentDate->modify("+$index day"); // Add a day for each iteration

                    // Assign the date to the 'transmiAdv' property
                    $customerInstruction->setTransmiAdv(clone $currentDate);
                    // Assign a random or static comment
                    $customerInstruction->setCustomerComment('Lorem ipsum dolor sit amet.');

                    // Persist the CustomerInstruction entity
                    $this->entityManager->persist($customerInstruction);

                    $carpetDesignOrder->setCustomerInstruction($customerInstruction);

                    // Create the associated CustomerConstraint entity with unique values for each CarpetDesignOrder
                    $customerConstraint = new CustomerConstraint();
                    $customerConstraint->setTransmittedPlan($this->generateRandomBoolean());
                    $customerConstraint->setLibTransmittedPlan($this->generateRandomNullableValue());
                    $customerConstraint->setPit($this->generateRandomBoolean());
                    $customerConstraint->setLibPit($this->generateRandomNullableValue());
                    $customerConstraint->setLineHeight($this->generateRandomBoolean());
                    $customerConstraint->setLibLineHeight($this->generateRandomNullableValue());
                    $customerConstraint->setLibSpecialThickness($this->generateRandomNullableValue());
                    $customerConstraint->setSpecialThickness($this->generateRandomBoolean());
                    $customerConstraint->setOtherCarpetInTheRoom($this->generateRandomBoolean());
                    $customerConstraint->setLibOtherCarpetInTHeRoom($this->generateRandomNullableValue());
                    $customerConstraint->setMiniLength($this->generateRandomBoolean());
                    $customerConstraint->setMaxiLength($this->generateRandomBoolean());
                    $customerConstraint->setMiniWidth($this->generateRandomBoolean());
                    $customerConstraint->setMaxiWidth($this->generateRandomBoolean());
                    $customerConstraint->setDstWallHeight($this->generateRandomNullableValue());
                    $customerConstraint->setDstWallDown($this->generateRandomNullableValue());
                    $customerConstraint->setDstWallLeft($this->generateRandomNullableValue());
                    $customerConstraint->setDstWallRight($this->generateRandomNullableValue()); // Link to the CustomerInstruction entity
                    $customerConstraint->setCustomerInstruction($customerInstruction);
                    // Persist the CustomerConstraint entity
                    $this->entityManager->persist($customerConstraint);
                    // Create the associated ValidatedSample entity
                    $validatedSample = new ValidatedSample();
                    $validatedSample->setRnValidatedSample('RN-' . random_int(1000, 9999)); // Random value for rnValidatedSample
                    $validatedSample->setColor($this->generateRandomBoolean());
                    $validatedSample->setLibColor('Color ' . random_int(1, 10));
                    $validatedSample->setVelvet($this->generateRandomBoolean());
                    $validatedSample->setLibVelvet('Velvet ' . random_int(1, 5));
                    $validatedSample->setMaterial($this->generateRandomBoolean());
                    $validatedSample->setLibMaterial('Material ' . random_int(1, 10));
                    $validatedSample->setCustomerNoteOnSample('Sample note ' . random_int(1, 100));

                    // Link the ValidatedSample to the CustomerInstruction
                    $validatedSample->setCustomerInstruction($customerInstruction);

                    // Persist the ValidatedSample entity
                    $this->entityManager->persist($validatedSample);

                    $finishing = new Finishing();
                    $finishing->setFabricColor('Color ' . random_int(1, 10)); // Set fabric color to a random value
                    $finishing->setFringe(1 === random_int(0, 1)); // Randomly set fringe (true or false)
                    $finishing->setWithoutBanking(1 === random_int(0, 1)); // Randomly set without banking (true or false)
                    $finishing->setNoBinding(1 === random_int(0, 1)); // Randomly set no binding (true or false)
                    $finishing->setMzCarved(1 === random_int(0, 1)); // Randomly set mzCarved (true or false)
                    $finishing->setOtherCarvedSignature('Carving ' . random_int(1, 10)); // Random carving signature
                    $finishing->setStandardVelvetHeight((string) random_int(1, 10) . '.' . random_int(100000, 999999)); // Random velvet height
                    $finishing->setSpecialVelvetHeight((string) random_int(1, 10) . '.' . random_int(100000, 999999)); // Random special velvet height

                    // Set the associated CustomerInstruction
                    $finishing->setCustomerInstruction($customerInstruction);

                    // Persist the Finishing entity
                    $this->entityManager->persist($finishing);
                    $this->entityManager->initializeObject($carpetDesignOrder->getProjectDi());
                    $this->entityManager->persist($carpetDesignOrder);
                    // Flush every 100 items to avoid memory overflow and clear the entities from memory
                    if (0 === $index % 100) {
                        $this->entityManager->flush();
                    }
                } catch (Throwable) {
                }
            }
        }

        // Final flush to ensure all remaining entities are persisted
        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success('All CustomerInstruction, CustomerConstraint, Finishing, and ValidatedSample entities have been successfully created.');

        return Command::SUCCESS;
    }

    /**
     * Generate a random boolean value for the customerConstraints.
     */
    private function generateRandomBoolean(): bool
    {
        return (bool) random_int(0, 1); // Randomly returns true or false
    }

    /**
     * Generate a random nullable value for the wall height/direction customerConstraints.
     *
     * @return float|null
     */
    private function generateRandomNullableValue()
    {
        return random_int(0, 1) ? random_int(100, 300) / 10 : null; // Random value or null
    }
}
