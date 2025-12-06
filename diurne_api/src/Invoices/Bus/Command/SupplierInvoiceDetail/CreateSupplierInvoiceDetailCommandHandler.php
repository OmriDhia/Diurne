<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Entity\SupplierInvoiceDetail;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;
use App\Invoices\Repository\SupplierInvoiceRepository;
use App\Workshop\Repository\CarpetRepository;
use InvalidArgumentException;

class CreateSupplierInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SupplierInvoiceRepository $invoiceRepository,
        private readonly SupplierInvoiceDetailRepository $detailRepository,
        private readonly CarpetRepository $carpetRepository
    ) {
    }

    public function __invoke(CreateSupplierInvoiceDetailCommand $command): SupplierInvoiceDetailResponse
    {
        $invoice = $this->invoiceRepository->find($command->supplierInvoiceId);
        if (null === $invoice) {
            throw new InvalidArgumentException('Supplier invoice not found');
        }

        $carpet = $this->carpetRepository->find($command->rnId);
        if (null === $carpet) {
            throw new InvalidArgumentException('Carpet not found');
        }

        $detail = new SupplierInvoiceDetail();
        $detail->setSupplierInvoice($invoice)
            ->setRn($carpet)
            ->setCarpetNumber($command->carpetNumber)
            ->setPricePerSquareMeter($command->pricePerSquareMeter)
            ->setInvoiceSurface($command->invoiceSurface)
            ->setInvoiceAmount($command->invoiceAmount)
            ->setTheoreticalPrice($command->theoreticalPrice)
            ->setPenalty($command->penalty)
            ->setProducedSurface($command->producedSurface)
            ->setActualCreditAmount($command->actualCreditAmount)
            ->setTheoreticalCredit($command->theoreticalCredit)
            ->setFinalCarpetAmount($command->finalCarpetAmount)
            ->setWeight($command->weight)
            ->setWeightPercentage($command->weightPercentage)
            ->setFreight($command->freight)
            ->setCleared($command->cleared);

        $this->detailRepository->persist($detail);
        $this->detailRepository->flush();

        return new SupplierInvoiceDetailResponse($detail);
    }
}
