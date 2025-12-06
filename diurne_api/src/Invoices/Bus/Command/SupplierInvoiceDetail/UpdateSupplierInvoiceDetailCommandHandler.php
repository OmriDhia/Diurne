<?php

namespace App\Invoices\Bus\Command\SupplierInvoiceDetail;

use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;
use App\Invoices\Repository\SupplierInvoiceRepository;
use App\Workshop\Repository\CarpetRepository;
use InvalidArgumentException;

class UpdateSupplierInvoiceDetailCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SupplierInvoiceRepository $invoiceRepository,
        private readonly SupplierInvoiceDetailRepository $detailRepository,
        private readonly CarpetRepository $carpetRepository
    ) {
    }

    public function __invoke(UpdateSupplierInvoiceDetailCommand $command): SupplierInvoiceDetailResponse
    {
        $detail = $this->detailRepository->find($command->id);
        if (null === $detail) {
            throw new InvalidArgumentException('Supplier invoice detail not found');
        }

        if (null !== $command->supplierInvoiceId) {
            $invoice = $this->invoiceRepository->find($command->supplierInvoiceId);
            if (null === $invoice) {
                throw new InvalidArgumentException('Supplier invoice not found');
            }
            $detail->setSupplierInvoice($invoice);
        }
        if (null !== $command->rnId) {
            $carpet = $this->carpetRepository->find($command->rnId);
            if (null === $carpet) {
                throw new InvalidArgumentException('Carpet not found');
            }
            $detail->setRn($carpet);
        }
        if (null !== $command->carpetNumber) {
            $detail->setCarpetNumber($command->carpetNumber);
        }
        if (null !== $command->pricePerSquareMeter) {
            $detail->setPricePerSquareMeter($command->pricePerSquareMeter);
        }
        if (null !== $command->invoiceSurface) {
            $detail->setInvoiceSurface($command->invoiceSurface);
        }
        if (null !== $command->invoiceAmount) {
            $detail->setInvoiceAmount($command->invoiceAmount);
        }
        if (null !== $command->theoreticalPrice) {
            $detail->setTheoreticalPrice($command->theoreticalPrice);
        }
        if (null !== $command->penalty) {
            $detail->setPenalty($command->penalty);
        }
        if (null !== $command->producedSurface) {
            $detail->setProducedSurface($command->producedSurface);
        }
        if (null !== $command->actualCreditAmount) {
            $detail->setActualCreditAmount($command->actualCreditAmount);
        }
        if (null !== $command->theoreticalCredit) {
            $detail->setTheoreticalCredit($command->theoreticalCredit);
        }
        if (null !== $command->finalCarpetAmount) {
            $detail->setFinalCarpetAmount($command->finalCarpetAmount);
        }
        if (null !== $command->weight) {
            $detail->setWeight($command->weight);
        }
        if (null !== $command->weightPercentage) {
            $detail->setWeightPercentage($command->weightPercentage);
        }
        if (null !== $command->freight) {
            $detail->setFreight($command->freight);
        }
        if (null !== $command->cleared) {
            $detail->setCleared($command->cleared);
        }

        $this->detailRepository->persist($detail);
        $this->detailRepository->flush();

        return new SupplierInvoiceDetailResponse($detail);
    }
}
