<?php

namespace App\Setting\Bus\Command\SpecialTreatment;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\SpecialTreatmentRepository;

class DeleteSpecialTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly SpecialTreatmentRepository $specialtreatmentRepository) {}

    public function __invoke(DeleteSpecialTreatmentCommand $command): SpecialTreatmentResponse
    {
        $specialtreatment = $this->specialtreatmentRepository->find($command->id);
        if (!$specialtreatment) {
            throw new RuntimeException('SpecialTreatment not found', 404);
        }

        try {
            $this->specialtreatmentRepository->remove($specialtreatment);
            $this->specialtreatmentRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete specialtreatment: ' . $e->getMessage(), 0, $e);
        }

        return new SpecialTreatmentResponse($specialtreatment);
    }
}
