<?php

namespace App\Setting\Bus\Command\SpecialTreatment;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\SpecialTreatmentRepository;

class CreateSpecialTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly SpecialTreatmentRepository $specialTreatmentRepository)
    {
    }

    public function __invoke(CreateSpecialTreatmentCommand $command): SpecialTreatmentResponse
    {
        $specialTreatment = new SpecialTreatment();
        $specialTreatment->setLabel($command->getLabel());
        $specialTreatment->setPrice($command->getPrice());
        $specialTreatment->setUnit($command->getUnit());

        $this->specialTreatmentRepository->save($specialTreatment, true);

        return new SpecialTreatmentResponse($specialTreatment);
    }
}
