<?php

namespace App\Contremarque\Bus\Command\CarpetSpecificTreatment;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use App\Contremarque\Repository\CarpetSpecificTreatmentRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Setting\Repository\SpecialTreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateCarpetSpecificTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarpetSpecificTreatmentRepository $carpetSpecificTreatmentRepository, private readonly QuoteDetailRepository $quoteDetailRepository, private readonly SpecialTreatmentRepository $specialTreatmentRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateCarpetSpecificTreatmentCommand $command): CarpetSpecificTreatmentResponse
    {
        $quoteDetail = $this->quoteDetailRepository->find($command->getQuoteDetailId());
        if (!$quoteDetail) {
            throw new Exception('QuoteDetail not found');
        }

        $treatment = $this->specialTreatmentRepository->find($command->getTreatmentId());
        if (!$treatment) {
            throw new Exception('Treatment not found');
        }

        $carpetSpecificTreatment = new CarpetSpecificTreatment();
        $carpetSpecificTreatment->setQuoteDetail($quoteDetail);
        $carpetSpecificTreatment->setTreatment($treatment);
        $carpetSpecificTreatment->setUnitPrice($treatment->getPrice());
        $carpetSpecificTreatment->setTotalPrice($treatment->getPrice() * $quoteDetail->getSurface());

        $this->entityManager->persist($carpetSpecificTreatment);
        $this->entityManager->flush();

        return new CarpetSpecificTreatmentResponse($carpetSpecificTreatment);
    }
}
