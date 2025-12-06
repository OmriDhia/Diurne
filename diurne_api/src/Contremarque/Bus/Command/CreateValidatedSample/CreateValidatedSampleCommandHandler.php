<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateValidatedSample;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\ValidatedSample;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\ValidatedSampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateValidatedSampleCommandHandler implements CommandHandler
{
    public function __construct(private readonly ValidatedSampleRepository $validatedSampleRepository, private readonly CustomerInstructionRepository $customerInstructionRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateValidatedSampleCommand $command): CreateValidatedSampleResponse
    {
        // Fetch the CustomerInstruction entity by ID
        $customerInstruction = $this->customerInstructionRepository->find($command->customerInstructionId);

        if (!$customerInstruction) {
            throw new InvalidArgumentException('Customer Instruction not found.');
        }
        // Créer une nouvelle entité ValidatedSample
        $validatedSample = new ValidatedSample();
        $validatedSample->setRnValidatedSample($command->rnValidatedSample)
            ->setColor($command->color)
            ->setLibColor($command->libColor)
            ->setVelvet($command->velvet)
            ->setLibVelvet($command->libVelvet)
            ->setMaterial($command->material)
            ->setLibMaterial($command->libMaterial)
            ->setCustomerNoteOnSample($command->customerNoteOnSample)
            ->setCustomerInstruction($customerInstruction);
        // Persister la nouvelle entité
        $this->entityManager->persist($validatedSample);
        $this->entityManager->flush();

        // Retourner la réponse contenant l'entité créée
        return new CreateValidatedSampleResponse($validatedSample);
    }
}
