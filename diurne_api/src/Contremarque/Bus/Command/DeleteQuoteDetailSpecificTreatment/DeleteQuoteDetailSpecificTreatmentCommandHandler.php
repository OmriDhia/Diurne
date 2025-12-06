<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteQuoteDetailSpecificTreatment;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetSpecificTreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteQuoteDetailSpecificTreatmentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetSpecificTreatmentRepository $carpetSpecificTreatmentRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(DeleteQuoteDetailSpecificTreatmentCommand $command): void
    {
        // Delete specific treatments
        $specificTreatment = $this->carpetSpecificTreatmentRepository->find((int)$command->specificTreatmentId);
        if (!$specificTreatment) {
            throw new ResourceNotFoundException('Specific treatment not found');
        }
        $this->carpetSpecificTreatmentRepository->remove($specificTreatment);
        $this->entityManager->flush();
    }
}
