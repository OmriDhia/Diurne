<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateConstraint;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Constraint;
use App\Contremarque\Entity\CustomerConstraint;
use App\Contremarque\Entity\CustomerInstruction;
use App\Contremarque\Repository\CustomerInstructionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateConstraintCommandHandler implements CommandHandler
{
    public function __construct(private readonly CustomerInstructionRepository $customerInstructionRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateConstraintCommand $command): CreateConstraintResponse
    {
        // Fetch the CustomerInstruction entity by ID
        $customerInstruction = $this->customerInstructionRepository->find($command->customerInstructionId);

        if (!$customerInstruction) {
            throw new InvalidArgumentException('Customer Instruction not found.');
        }

        // Create and populate the Constraint entity
        $constraint = new CustomerConstraint();
        $constraint->setCustomerInstruction($customerInstruction)
            ->setTransmittedPlan($command->transmittedPlan)
            ->setLibTransmittedPlan($command->libTransmittedPlan)
            ->setPit($command->pit)
            ->setLibPit($command->libPit)
            ->setLineHeight($command->lineHeight)
            ->setLibLineHeight($command->libLineHeight)
            ->setSpecialThickness($command->specialThickness)
            ->setLibSpecialThickness($command->libSpecialThickness)
            ->setOtherCarpetInTheRoom($command->otherCarpetInTheRoom)
            ->setLibOtherCarpetInTheRoom($command->libOtherCarpetInTheRoom)
            ->setMiniLength($command->miniLength)
            ->setMaxiLength($command->maxiLength)
            ->setMiniWidth($command->miniWidth)
            ->setMaxiWidth($command->maxiWidth)
            ->setDstWallHeight($command->dstWallHeight)
            ->setDstWallDown($command->dstWallDown)
            ->setDstWallLeft($command->dstWallLeft)
            ->setDstWallRight($command->dstWallRight);

        // Persist the new entity
        $this->entityManager->persist($constraint);
        $this->entityManager->flush();

        // Return the response containing the created constraint
        return new CreateConstraintResponse($constraint);
    }
}
