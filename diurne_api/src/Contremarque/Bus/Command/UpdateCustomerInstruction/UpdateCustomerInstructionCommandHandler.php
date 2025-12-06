<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateCustomerInstruction;

use InvalidArgumentException;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CustomerConstraint;
use App\Contremarque\Entity\Finishing;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Entity\ValidatedSample;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CustomerConstraintRepository;
use App\Contremarque\Repository\CustomerInstructionRepository;
use App\Contremarque\Repository\FinishingRepository;
use App\Contremarque\Repository\ValidatedSampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateCustomerInstructionCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository, private readonly CustomerInstructionRepository $customerInstructionRepository, private readonly ValidatedSampleRepository $validatedSampleRepository, private readonly FinishingRepository $finishingRepository, private readonly CustomerConstraintRepository $constraintRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(UpdateCustomerInstructionCommand $command): UpdateCustomerInstructionResponse
    {
        $customerInstruction = $this->customerInstructionRepository->find($command->getCustomerInstructionId());
        // Fetch the CarpetDesignOrder entity by ID
        if (!empty($command->getCarpetDesignOrderId())) {
            $carpetDesignOrder = $this->carpetDesignOrderRepository->find($command->getCarpetDesignOrderId());
            if ($carpetDesignOrder && $command->getObjectType() === 'CarpetDesignOrder') {
                $customerInstruction->setCarpetDesignOrder($carpetDesignOrder);
                $carpetDesignOrder->setCustomerInstruction($customerInstruction);
            }
            $sample = $this->entityManager->find(Sample::class, $command->getCarpetDesignOrderId());
            if ($sample && $command->getObjectType() === 'Sample') {
                $customerInstruction->setSample($sample);
            }
        }

        if (!$customerInstruction) {
            throw new InvalidArgumentException('Customer Instruction not found.');
        }

        // Update fields only if they are not null
        if (null !== $command->getOrderNumber()) {
            $customerInstruction->setOrderNumber($command->getOrderNumber());
        }

        if (null !== $command->getTransmissionAdvice()) {
            $customerInstruction->setTransmiAdv($command->getTransmissionAdvice());
        }

        if (null !== $command->getCustomerComment()) {
            $customerInstruction->setCustomerComment($command->getCustomerComment());
        }

        if (null !== $command->getCustomerValidationDate()) {
            $customerValidationDate = new DateTimeImmutable($command->getCustomerValidationDate());
            $customerInstruction->setCustomerValidationDate($customerValidationDate);
        }

        if (null !== $command->hasConstraints()) {
            $customerInstruction->setHasCustomerConstraints($command->hasConstraints());
        }

        if (null !== $command->hasValidateSample()) {
            $customerInstruction->setHasValidateSample($command->hasValidateSample());
        }

        if (null !== $command->hasFinitionInstruction()) {
            $customerInstruction->setHasFinitionInstruction($command->hasFinitionInstruction());
        }

        if (!empty($command->getValidatedSampleId())) {
            $validatedSample = $this->validatedSampleRepository->find((int)$command->getValidatedSampleId());
            if ($validatedSample instanceof ValidatedSample) {
                $customerInstruction->setValidatedSample($validatedSample);
            }
        }

        if (!empty($command->getFinitionInstructionId())) {
            $finishing = $this->finishingRepository->find((int)$command->getFinitionInstructionId());
            if ($finishing instanceof Finishing) {
                $customerInstruction->setFinitionInstruction($finishing);
            }
        }

        if (!empty($command->getConstraintInstructionId())) {
            $constraint = $this->constraintRepository->find((int)$command->getConstraintInstructionId());
            if ($constraint instanceof CustomerConstraint) {
                $customerInstruction->setCustomerConstraint($constraint);
            }
        }

        // Persist changes
        $this->entityManager->persist($customerInstruction);
        $this->entityManager->flush();

        return new UpdateCustomerInstructionResponse($customerInstruction);
    }
}
