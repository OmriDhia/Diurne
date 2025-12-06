<?php

namespace App\Invoices\Bus\Command\CustomerInvoice;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Invoices\Repository\CustomerInvoiceRepository;
use App\Setting\Repository\CarrierRepository;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Repository\CarpetOrderRepository;
use App\Contremarque\Repository\InvoiceTypeRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\ConversionRepository;
use App\Setting\Repository\LanguageRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\RegulationRepository;
use App\Contremarque\Repository\TarifExpeditionRepository;
use App\Workshop\Repository\CarpetRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\Common\Exception\ResourceNotFoundException;

class UpdateCustomerInvoiceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CustomerInvoiceRepository $repository,
        private readonly CarrierRepository $carrierRepository,
        private readonly CustomerRepository $customerRepository,
        private readonly CarpetOrderRepository $carpetOrderRepository,
        private readonly InvoiceTypeRepository $invoiceTypeRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly ConversionRepository $conversionRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly MesurementRepository $mesurementRepository,
        private readonly RegulationRepository $regulationRepository,
        private readonly TarifExpeditionRepository $tarifExpeditionRepository,
        private readonly CarpetRepository $carpetRepository,
        private readonly TaxRuleRepository $taxRuleRepository
    ) {
    }

    public function __invoke(UpdateCustomerInvoiceCommand $command): CustomerInvoiceResponse
    {
        $invoice = $this->repository->find($command->id);
        if (!$invoice) {
            throw new ResourceNotFoundException();
        }
        $carrier = $this->carrierRepository->find($command->carrierId);
        $customer = $this->customerRepository->find($command->customerId);
        $carpetOrder = null;
        if (null !== $command->carpetOrderId) {
            $carpetOrder = $this->carpetOrderRepository->find($command->carpetOrderId);
        }

        $prescriber = $command->prescriberId ? $this->customerRepository->find($command->prescriberId) : null;
        $invoiceTypeEntity = $command->invoiceTypeEntityId ? $this->invoiceTypeRepository->find($command->invoiceTypeEntityId) : null;
        $currency = $command->currencyId ? $this->currencyRepository->find($command->currencyId) : null;
        $conversion = $command->conversionId ? $this->conversionRepository->find($command->conversionId) : null;
        $language = $command->languageId ? $this->languageRepository->find($command->languageId) : null;
        $mesurement = $command->mesurementId ? $this->mesurementRepository->find($command->mesurementId) : null;
        $regulation = $command->regulationId ? $this->regulationRepository->find($command->regulationId) : null;
        $tarifExpedition = $command->tarifExpeditionId ? $this->tarifExpeditionRepository->find($command->tarifExpeditionId) : null;
        $rn = $command->rnId ? $this->carpetRepository->find($command->rnId) : null;
        $taxRule = $command->taxRuleId ? $this->taxRuleRepository->find($command->taxRuleId) : null;

        $invoice->setInvoiceNumber($command->invoiceNumber)
            ->setInvoiceDate($command->invoiceDate)
            ->setInvoiceType($command->invoiceType)
            ->setCarrier($carrier)
            ->setCustomer($customer)
            ->setCarpetOrder($carpetOrder)
            ->setPrescriber($prescriber)
            ->setInvoiceTypeEntity($invoiceTypeEntity)
            ->setCurrency($currency)
            ->setConversion($conversion)
            ->setLanguage($language)
            ->setLmesurement($mesurement)
            ->setRegulation($regulation)
            ->setTarifExpedition($tarifExpedition)
            ->setRn($rn)
            ->setTaxRule($taxRule)
            ->setDescription($command->description)
            ->setNumber($command->number)
            ->setProject($command->project)
            ->setQuantityTotal($command->quantityTotal)
            ->setShippingCostsHt($command->shippingCostsHt)
            ->setBilled($command->billed)
            ->setPayment($command->payment)
            ->setTotalHt($command->totalHt)
            ->setAmountHt($command->amountHt)
            ->setAmountTva($command->amountTva)
            ->setAmountTtc($command->amountTtc)
            ->setUpdatedAt(new DateTimeImmutable());

        if (null !== $carpetOrder) {
            $commercialId = $carpetOrder->getCommercialId() ?? $carpetOrder->getCurrentCommercialId();
            $invoice->setCommercialId($commercialId);
        }

        $this->repository->persist($invoice);
        $this->repository->flush();

        return new CustomerInvoiceResponse($invoice);
    }
}
