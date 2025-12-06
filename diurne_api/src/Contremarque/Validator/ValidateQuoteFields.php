<?php

namespace App\Contremarque\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ValidateQuoteFields extends Constraint
{
    public string $messageShippingPrice = 'Shipping price is required for "Transport quoté" condition.';
    public string $messageConversionId = 'Conversion ID is required when currency is Dollars.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
