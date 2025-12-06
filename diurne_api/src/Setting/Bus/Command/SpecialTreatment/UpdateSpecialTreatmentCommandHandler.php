<?php

namespace App\Setting\Bus\Command\SpecialTreatment;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\SpecialTreatmentRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateSpecialTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly SpecialTreatmentRepository $specialTreatmentRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateSpecialTreatmentCommand $command): SpecialTreatmentResponse
    {
        $specialTreatment = $this->specialTreatmentRepository->find($command->getId());

        if (null === $specialTreatment) {
            throw new ResourceNotFoundException();
        }
        $specialTreatment->setLabel($command->getLabel());
        $specialTreatment->setPrice($command->getPrice());
        $specialTreatment->setUnit($command->getUnit());

        $this->specialTreatmentRepository->save($specialTreatment, true);

        return new SpecialTreatmentResponse($specialTreatment);
    }
}
