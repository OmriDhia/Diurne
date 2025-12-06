<?php

namespace App\Contremarque\EventListener;

use App\Contremarque\Entity\CustomerInstruction;
use App\Contremarque\Repository\CustomerConstraintRepository;
use App\Contremarque\Repository\FinishingRepository;
use App\Contremarque\Repository\ValidatedSampleRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDoctrineListener; // Import the correct attribute

#[AsDoctrineListener]
class CustomerInstructionListener
{
    public function __construct(
        private readonly CustomerConstraintRepository $constraintRepository,
        private readonly FinishingRepository $finishingRepository,
        private readonly ValidatedSampleRepository $validatedSampleRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof CustomerInstruction) {
            return;
        }

        $this->cleanUnusedEntities();
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof CustomerInstruction) {
            return;
        }

        $this->cleanUnusedEntities();
    }

    private function cleanUnusedEntities(): void
    {
        // Delete unused constraints
        $unusedConstraints = $this->constraintRepository->findUnusedConstraints();
        foreach ($unusedConstraints as $unusedConstraint) {
            $this->entityManager->remove($unusedConstraint);
        }

        // Delete unused finitions
        $unusedFinitions = $this->finishingRepository->findUnusedFinitions();
        foreach ($unusedFinitions as $unusedFinition) {
            $this->entityManager->remove($unusedFinition);
        }

        // Delete unused validated samples
        $unusedValidatedSamples = $this->validatedSampleRepository->findUnusedValidatedSamples();
        foreach ($unusedValidatedSamples as $unusedValidatedSample) {
            $this->entityManager->remove($unusedValidatedSample);
        }

        $this->entityManager->flush();
    }
}
