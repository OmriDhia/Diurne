<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateConstraint;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\Constraint;
use App\Contremarque\Repository\CustomerConstraintRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateConstraintCommandHandler implements CommandHandler
{
    public function __construct(private readonly CustomerConstraintRepository $constraintRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(UpdateConstraintCommand $command): UpdateConstraintResponse
    {
        // Fetch the Constraint entity by ID
        $constraint = $this->constraintRepository->find($command->getConstraintId());

        if (!$constraint) {
            throw new InvalidArgumentException('Constraint not found.');
        }

        // Update the fields of the Constraint entity only if the value is not null or empty
        if (null !== $command->getTransmittedPlan()) {
            $constraint->setTransmittedPlan($command->getTransmittedPlan());
        }

        if (!empty($command->getLibTransmittedPlan())) {
            $constraint->setLibTransmittedPlan($command->getLibTransmittedPlan());
        }

        if (null !== $command->getPit()) {
            $constraint->setPit($command->getPit());
        }

        if (!empty($command->getLibPit())) {
            $constraint->setLibPit($command->getLibPit());
        }

        if (null !== $command->getLineHeight()) {
            $constraint->setLineHeight($command->getLineHeight());
        }

        if (!empty($command->getLibLineHeight())) {
            $constraint->setLibLineHeight($command->getLibLineHeight());
        }

        if (null !== $command->getSpecialThickness()) {
            $constraint->setSpecialThickness($command->getSpecialThickness());
        }

        if (!empty($command->getLibSpecialThickness())) {
            $constraint->setLibSpecialThickness($command->getLibSpecialThickness());
        }

        if (null !== $command->getOtherCarpetInTheRoom()) {
            $constraint->setOtherCarpetInTheRoom($command->getOtherCarpetInTheRoom());
        }

        if (!empty($command->getLibOtherCarpetInTheRoom())) {
            $constraint->setLibOtherCarpetInTheRoom($command->getLibOtherCarpetInTheRoom());
        }

        if (null !== $command->getMiniLength()) {
            $constraint->setMiniLength($command->getMiniLength());
        }

        if (null !== $command->getMaxiLength()) {
            $constraint->setMaxiLength($command->getMaxiLength());
        }

        if (null !== $command->getMiniWidth()) {
            $constraint->setMiniWidth($command->getMiniWidth());
        }

        if (null !== $command->getMaxiWidth()) {
            $constraint->setMaxiWidth($command->getMaxiWidth());
        }

        if (!empty($command->getDstWallHeight())) {
            $constraint->setDstWallHeight($command->getDstWallHeight());
        }

        if (!empty($command->getDstWallDown())) {
            $constraint->setDstWallDown($command->getDstWallDown());
        }

        if (!empty($command->getDstWallLeft())) {
            $constraint->setDstWallLeft($command->getDstWallLeft());
        }

        if (!empty($command->getDstWallRight())) {
            $constraint->setDstWallRight($command->getDstWallRight());
        }

        // Persist the updated entity
        $this->entityManager->flush();

        // Return the response containing the updated constraint
        return new UpdateConstraintResponse($constraint);
    }
}
