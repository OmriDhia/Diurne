<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\SpecialShape;

use App\Common\Exception\ValidationException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\SpecialShape;
use App\Setting\Repository\SpecialShapeRepository;

class CreateSpecialShapeCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SpecialShapeRepository $specialShapeRepository
    ) {
    }

    public function __invoke(CreateSpecialShapeCommand $command): SpecialShapeResponse
    {
        $existingSpecialShape = $this->specialShapeRepository->findOneByLabel($command->getLabel());

        if ($existingSpecialShape instanceof SpecialShape) {
            throw new ValidationException(['There is already a special shape with the same label.']);
        }

        $specialShape = new SpecialShape();
        $specialShape->setLabel($command->getLabel());

        $this->specialShapeRepository->persist($specialShape);
        $this->specialShapeRepository->flush();

        return new SpecialShapeResponse($specialShape);
    }
}
