<?php

namespace App\Contremarque\Validator;

use App\Contremarque\DTO\CreateQuoteRequestDto;
use App\Contremarque\Enum\CurrencyEnum;
use App\Contremarque\Enum\TransportConditionEnum;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\TransportConditionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidateQuoteFieldsValidator extends ConstraintValidator
{
    public function __construct(
        private TransportConditionRepository $transportConditionRepository,
        private CurrencyRepository           $currencyRepository
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof CreateQuoteRequestDto) {
            return;
        }

        $this->validateShippingPrice($value, $constraint);
        $this->validateConversionId($value, $constraint);
    }

    private function validateShippingPrice(CreateQuoteRequestDto $dto, ValidateQuoteFields $constraint): void
    {
        $transportCondition = $this->transportConditionRepository->find($dto->transportConditionId);

        if ($transportCondition && in_array(
                $transportCondition->getName(),
                [
                    TransportConditionEnum::TRANSPORT_QUOTE_FR->value,
                    TransportConditionEnum::TRANSPORT_QUOTE_EN->value
                ],
                true
            )) {
            if ($dto->shippingPrice === null) {
                $this->context->buildViolation($constraint->messageShippingPrice)
                    ->atPath('shippingPrice')
                    ->addViolation();
            }
        }
    }

    private function validateConversionId(CreateQuoteRequestDto $dto, ValidateQuoteFields $constraint): void
    {
        $currency = $this->currencyRepository->find($dto->currencyId);

        if ($currency && $currency->getName() === CurrencyEnum::DOLLARS->value) {
            if ($dto->conversionId === null || $dto->conversionId === 0) {
                $this->context->buildViolation($constraint->messageConversionId)
                    ->atPath('conversionId')
                    ->addViolation();
            }
        }
    }
}