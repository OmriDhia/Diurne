<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateValidatedSample;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\ValidatedSample;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\ValidatedSampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateValidatedSampleCommandHandler implements CommandHandler
{
    public function __construct(private readonly ValidatedSampleRepository $validatedSampleRepository, private readonly CustomerInstructionRepository $customerInstructionRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(UpdateValidatedSampleCommand $command): UpdateValidatedSampleResponse
    {
        // Fetch the CustomerInstruction entity by ID
        $customerInstruction = $this->customerInstructionRepository->find($command->customerInstructionId);

        if (!$customerInstruction) {
            throw new InvalidArgumentException('Customer Instruction not found.');
        }
        // Retrieve the ValidatedSample entity by ID
        $validatedSample = $this->validatedSampleRepository->find((int) $command->id);

        if (!$validatedSample) {
            throw new InvalidArgumentException('Validated Sample not found.');
        }

        // Update the ValidatedSample fields if not null or empty
        if (null !== $command->rnValidatedSample) {
            $validatedSample->setRnValidatedSample($command->rnValidatedSample);
        }
        if (null !== $command->color) {
            $validatedSample->setColor($command->color);
        }
        if (null !== $command->libColor) {
            $validatedSample->setLibColor($command->libColor);
        }
        if (null !== $command->velvet) {
            $validatedSample->setVelvet($command->velvet);
        }
        if (null !== $command->libVelvet) {
            $validatedSample->setLibVelvet($command->libVelvet);
        }
        if (null !== $command->material) {
            $validatedSample->setMaterial($command->material);
        }
        if (null !== $command->libMaterial) {
            $validatedSample->setLibMaterial($command->libMaterial);
        }
        if (null !== $command->customerNoteOnSample) {
            $validatedSample->setCustomerNoteOnSample($command->customerNoteOnSample);
        }

        $this->entityManager->flush();

        // Return a response with the updated ValidatedSample
        return new UpdateValidatedSampleResponse($validatedSample);
    }
}
