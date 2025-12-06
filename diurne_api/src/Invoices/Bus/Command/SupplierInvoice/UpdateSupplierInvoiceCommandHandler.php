<?php

namespace App\Invoices\Bus\Command\SupplierInvoice;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\SupplierInvoiceRepository;
use App\Invoices\Repository\SupplierInvoicePricesRepository;
use App\Setting\Repository\CurrencyRepository;
use App\User\Repository\UserRepository;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ManufacturerRepository;

class UpdateSupplierInvoiceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly SupplierInvoiceRepository       $repository,
        private readonly SupplierInvoicePricesRepository $pricesRepository,
        private readonly CurrencyRepository              $currencyRepository,
        private readonly UserRepository                  $userRepository,
        private readonly ManufacturerRepository          $manufacturerRepository,
    )
    {
    }

    public function __invoke(UpdateSupplierInvoiceCommand $command): SupplierInvoiceResponse
    {
        $invoice = $this->repository->find($command->id);
        if (!$invoice) {
            throw new ResourceNotFoundException();
        }

        $invoice->setInvoiceNumber($command->invoiceNumber)
            ->setInvoiceDate($command->invoiceDate)
            ->setManufacturer(
                $command->manufacturerId ? $this->manufacturerRepository->find($command->manufacturerId) : $this->manufacturerRepository->find(2)
            )
            ->setPackingList($command->packingList)
            ->setAirWay($command->airWay)
            ->setFretTotal($command->fretTotal)
            ->setCurrency($this->currencyRepository->find((int)$command->currencyId))
            ->setAuthor($this->userRepository->find($command->authorId))
            ->setAmountOther($command->amountOther)
            ->setWeight($command->weight)
            ->setDescription($command->description)
            ->setIsincluded($command->isincluded)
            ->setWeightTotal($command->weightTotal)
            ->setSurfaceTotal($command->surfaceTotal)
            ->setInvoiceTotal($command->invoiceTotal)
            ->setTheoreticalTotal($command->theoreticalTotal)
            ->setUpdatedAt(new DateTimeImmutable());

        $prices = $invoice->getPrices() ?? new SupplierInvoicePrices();
        $prices->setAmountTheoretical($command->amountTheoretical)
            ->setAmountReal($command->amountReal)
            ->setCreditNember($command->creditNumber ?? 0)
            ->setCreditDate($command->creditDate ?? new DateTimeImmutable())
            ->setPaymentReal($command->paymentReal)
            ->setPaymentTheoretical($command->paymentTheoretical)
            ->setPaymentDate($command->paymentDate ?? new DateTimeImmutable());
        $invoice->setPrices($prices);

        $this->repository->persist($invoice);
        $this->repository->flush();

        return new SupplierInvoiceResponse($invoice);
    }
}
