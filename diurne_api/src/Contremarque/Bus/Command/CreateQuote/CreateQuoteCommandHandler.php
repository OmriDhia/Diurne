<?php

namespace App\Contremarque\Bus\Command\CreateQuote;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contact\Repository\AddressRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventNomenclatureRepository;
use App\Event\Repository\EventRepository;
use App\Setting\Entity\TransportCondition;
use App\Setting\Repository\ConversionRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\Setting\Repository\TransportConditionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateQuoteCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaxRuleRepository $taxRuleRepository,
        private readonly CurrencyRepository $currencyRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly AddressRepository $addressRepository,
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly DiscountRuleRepository $discountRuleRepository,
        private readonly QuoteRepository $quoteRepository,
        private readonly ConversionRepository $conversionRepository,
        private readonly TransportConditionRepository $transportConditionRepository,
        private readonly EventRepository $eventRepository,
        private readonly EventNomenclatureRepository $eventNomenclatureRepository
    ) {}

    public function __invoke(CreateQuoteCommand $command): CreateQuoteResponse
    {
        $dto = $command->dto;
        $contremarqueId = $command->contremarqueId;
        $contremarque = $this->contremarqueRepository->find((int) $contremarqueId);
        if (!$contremarque instanceof Contremarque) {
            throw new ValidationException(['Contremarque not found']);
        }

        $reference = $this->quoteRepository->getNextQuoteNumber();
        $quote = new Quote();

        $quote->setReference($reference)
            ->setWithoutDiscountPrice(!empty($dto->withoutDiscountPrice) ? $dto->withoutDiscountPrice : (string) 0)
            ->setAdditionalDiscount(!empty($dto->additionalDiscount) ? $dto->additionalDiscount : (string) 0)
            ->setTotalDiscountAmount(!empty($dto->totalDiscountAmount) ? $dto->totalDiscountAmount : (string) 0)
            ->setTotalDiscountPercentage(!empty($dto->totalDiscountPercentage) ? $dto->totalDiscountPercentage : (string) 0)
            ->setTotalTaxExcluded(!empty($dto->totalTaxExcluded) ? $dto->totalTaxExcluded : (string) 0)
            ->setShippingPrice(!empty($dto->shippingPrice) ? $dto->shippingPrice : (string) 0)
            ->setTax(!empty($dto->tax) ? $dto->tax : (string) 0)
            ->setTotalTaxIncluded(!empty($dto->totalTaxIncluded) ? $dto->totalTaxIncluded : (string) 0)
            ->setQuoteSentToCustomer(!empty($dto->quoteSentToCustomer) ? $dto->quoteSentToCustomer : false)
            ->setTransformedIntoAnOrder($dto->transformedIntoAnOrder)
            ->setArchived($dto->archived);
        $quote->setCreatedAt();
        $quote->setIsValidated($dto->isValidated ?? false);
        if ($dto->qualificationMessage !== null) {
            $quote->setQualificationMessage($dto->qualificationMessage);
        }
        if ($dto->weight !== null) {
            $quote->setWeight((string)$dto->weight);
        }
        if ($dto->additionalDiscount !== null) {
            $quote->setAdditionalDiscount((string)$dto->additionalDiscount);
        }
        if ($dto->shippingPrice !== null) {
            $quote->setShippingPrice((string)$dto->shippingPrice);
        }
        $discountRule = $contremarque->getCustomer()->getDiscountRule();
        if (!empty($command->dto->discountRuleId)) {
            $discountRule = $this->discountRuleRepository->find((int) $command->dto->discountRuleId);
        }

        $quote->setDiscountRule($discountRule);
        $quote->setQualificationMessage($dto->qualificationMessage);

        // Fetch associated entities
        $taxRule = $this->taxRuleRepository->find($dto->taxRuleId);
        $currency = $this->currencyRepository->find($dto->currencyId);
        $language = $this->languageRepository->find($dto->languageId);
        $deliveryAddress = $dto->deliveryAddressId ? $this->addressRepository->find($dto->deliveryAddressId) : null;
        $invoiceAddress = $dto->invoiceAddressId ? $this->addressRepository->find($dto->invoiceAddressId) : null;

        // Set the associations
        $quote->setTaxRule($taxRule)
            ->setCurrency($currency)
            ->setLanguage($language)
            ->setUnitOfmeasurement($dto->unitOfMeasurement)
            ->setDeliveryAddress($deliveryAddress)
            ->setInvoiceAddress($invoiceAddress);
        if (!empty($dto->conversionId)) {
            $conversion = $this->conversionRepository->find((int) $dto->conversionId);
            $quote->setConversion($conversion);
        }
        if (!empty($dto->transportConditionId)) {
            $transportCondition = $this->transportConditionRepository->find((int) $dto->transportConditionId);
            if (!$transportCondition instanceof TransportCondition) {
                throw new ValidationException(['TransportCondition not found']);
            }
            $quote->setTransportCondition($transportCondition);
        }
        // Définir le commercialId du devis
        $contremarqueCommercialId = $contremarque->getCommercialId();
        if ($contremarqueCommercialId) {
            $quote->setCommercialId($contremarqueCommercialId);
        } else {
            // Récupérer le commercial actuel du client si pas défini dans la contremarque
            $customer = $contremarque->getCustomer();
            $latestCommercialHistory = null;
            
            foreach ($customer->getContactCommercialHistories() as $history) {
                if ($history->getStatus()->getName() === 'Accepted' && $history->getToDate() === null) {
                    $latestCommercialHistory = $history;
                    break;
                }
            }
            
            if ($latestCommercialHistory) {
                $quote->setCommercialId($latestCommercialHistory->getCommercial()->getId());
            }
        }
        
        // Persist and save the quote
        $this->entityManager->persist($quote);
        $contremarque->addQuote($quote);
        $this->entityManager->persist($contremarque);
        $this->entityManager->flush();

        $nomenclature = $this->eventNomenclatureRepository->findBySubject('Nouveau Devis Client');

        $event = new Event();
        $event->setNomenclature($nomenclature);
        $event->setCustomer($contremarque->getCustomer());
        $event->setContremarque($contremarque);
        $event->setEventDate(new DateTimeImmutable());
        $event->setCommentaire('Devis : ' . $quote->getReference());
        $this->eventRepository->persist($event);
        $this->eventRepository->flush();

        return new CreateQuoteResponse($quote);
    }
}
