<?php

namespace App\Setting\Bus\Command\SpecialShape;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\SpecialShapeRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateSpecialShapeCommandHandler implements CommandHandler
{
    public function __construct(private readonly SpecialShapeRepository $specialShapeRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateSpecialShapeCommand $command): SpecialShapeResponse
    {
        $specialShape = $this->specialShapeRepository->find($command->getId());

        if (null === $specialShape) {
            throw new ResourceNotFoundException();
        }
        $specialShape->setLabel($command->getLabel());

        $this->specialShapeRepository->save($specialShape, true);

        return new SpecialShapeResponse($specialShape);
    }
}
