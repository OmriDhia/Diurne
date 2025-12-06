<?php

namespace App\Contremarque\Bus\Command\UpdateQuote;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Calculator\Utils\Tools;
use App\Common\Exception\ValidationException;
use App\Contact\Entity\Address;
use App\Contact\Repository\AddressRepository;

use App\Contremarque\Entity\Quote;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\QuoteRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Setting\Entity\Currency;
use App\Setting\Entity\DiscountRule;
use App\Setting\Entity\Language;
use App\Setting\Entity\TaxRule;
use App\Setting\Entity\TransportCondition;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\DiscountRuleRepository;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateQuoteCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface       $entityManager,
        private readonly QuoteRepository              $quoteRepository,
        private readonly ContremarqueRepository       $contremarqueRepository,
        private readonly DiscountRuleRepository       $discountRuleRepository,
        private readonly TaxRuleRepository            $taxRuleRepository,
        private readonly AddressRepository            $addressRepository,
        private readonly UnitOfMeasurementRepository  $unitOfMeasurementRepository,
        private readonly CurrencyRepository           $currencyRepository,
        private readonly LanguageRepository           $languageRepository,
        private readonly TransportConditionRepository $transportConditionRepository
    )
    {
    }

    public function __invoke(UpdateQuoteCommand $command): UpdateQuoteResponse
    {
        $dto = $command->dto;

        // Find and validate the quote
        $quote = $this->quoteRepository->find($command->quoteId);
        if (!$quote instanceof Quote) {
            throw new ValidationException(['Quote not found']);
        }

        // Update quote fields with values from DTO if they are not null
        if (null !== $dto->withoutDiscountPrice) {
            $quote->setWithoutDiscountPrice($dto->withoutDiscountPrice);
        }
        if (null !== $dto->additionalDiscount) {
            $quote->setAdditionalDiscount($dto->additionalDiscount);
        }
        if (null !== $dto->totalDiscountAmount) {
            $quote->setTotalDiscountAmount($dto->totalDiscountAmount);
        } else {
            $totalDiscountAmmount = Tools::ps_round((float)$quote->getCumulatedDiscountAmount() + (float)$quote->getAdditionalDiscount());
            $quote->setTotalDiscountAmount($totalDiscountAmmount);
        }
        if (null !== $dto->totalDiscountPercentage) {
            $quote->setTotalDiscountPercentage($dto->totalDiscountPercentage);
        }
        $totalTaxExcluded = Tools::ps_round((float)$quote->getWithoutDiscountPrice() - (float)$quote->getTotalDiscountAmount());
        $quote->setTotalTaxExcluded($totalTaxExcluded);

        if (null !== $dto->shippingPrice) {
            $quote->setShippingPrice($dto->shippingPrice);
        }

        if (null !== $dto->weight) {
            $quote->setWeight($dto->weight);
        }


        if (null !== $dto->quoteSentToCustomer) {
            $quote->setQuoteSentToCustomer($dto->quoteSentToCustomer);
        }
        if (null !== $dto->transformedIntoAnOrder) {
            $quote->setTransformedIntoAnOrder($dto->transformedIntoAnOrder);
        }
        if (null !== $dto->archived) {
            $quote->setArchived($dto->archived);
        }
        if (null !== $dto->qualificationMessage) {
            $quote->setQualificationMessage($dto->qualificationMessage);
        }
        if (!empty($dto->discountRuleId)) {
            $discountRule = $this->discountRuleRepository->find((int)$dto->discountRuleId);
            if (!$discountRule instanceof DiscountRule) {
                throw new ValidationException(['DiscountRule not found']);
            }
            $quote->setDiscountRule($discountRule);
        }
        if (!empty($dto->taxRuleId)) {
            $taxRule = $this->taxRuleRepository->find((int)$dto->taxRuleId);
            if (!$taxRule instanceof TaxRule) {
                throw new ValidationException(['TaxRule not found']);
            }
            $quote->setTaxRule($taxRule);

            $taxRate = $taxRule->getTaxRate();
            $totalTaxExcluded = Tools::ps_round((float)$quote->getWithoutDiscountPrice() - (float)$quote->getTotalDiscountAmount());
            $totalTaxIncluded = Tools::ps_round($totalTaxExcluded + ($totalTaxExcluded * (float)$taxRate));

            $quote->setTotalTaxIncluded($totalTaxIncluded);
            $quote->setTax(Tools::ps_round($totalTaxIncluded - $totalTaxExcluded));
        }
        if (!empty($dto->currencyId)) {
            $currency = $this->currencyRepository->find((int)$dto->currencyId);
            if (!$currency instanceof Currency) {
                throw new ValidationException(['Currency not found']);
            }
            $quote->setCurrency($currency);
        }
        if (!empty($dto->languageId)) {
            $language = $this->languageRepository->find((int)$dto->languageId);
            if (!$language instanceof Language) {
                throw new ValidationException(['Language not found']);
            }
            $quote->setLanguage($language);
        }
        if (!empty($dto->unitOfMeasurement)) {
            $quote->setUnitOfmeasurement($dto->unitOfMeasurement);
        }
        if (!empty($dto->deliveryAddressId)) {
            $deliveryAddress = $this->addressRepository->find((int)$dto->deliveryAddressId);
            if (!$deliveryAddress instanceof Address) {
                throw new ValidationException(['Address not found']);
            }
            $quote->setDeliveryAddress($deliveryAddress);
        }
        if (!empty($dto->invoiceAddressId)) {
            $invoiceAddress = $this->addressRepository->find((int)$dto->invoiceAddressId);
            if (!$invoiceAddress instanceof Address) {
                throw new ValidationException(['Invoice address not found']);
            }
            $quote->setInvoiceAddress($invoiceAddress);
        }
        if (!empty($dto->transportConditionId)) {
            $transportCondition = $this->transportConditionRepository->find((int)$dto->transportConditionId);
            if (!$transportCondition instanceof TransportCondition) {
                throw new ValidationException(['TransportCondition not found']);
            }
            $quote->setTransportCondition($transportCondition);
        }
        $this->entityManager->persist($quote);
        $this->entityManager->flush();

        return new UpdateQuoteResponse($quote);
    }
}
