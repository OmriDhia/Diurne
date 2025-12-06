<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\PostCode;
use App\Setting\Repository\CountryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PostCodeFormatValidator extends ConstraintValidator
{
    public function __construct(private readonly CountryRepository $countryRepository) {}

    public function validate($value, Constraint $constraint)
    {

        if (!$constraint instanceof PostCode) {
            return;
        }
        $countryId = $this->context->getObject()->countryId;
        $country = $this->countryRepository->find($countryId);

        if (!$country) {
            return;
        }

        $postalCodeFormat = $country->getZipCodeFormat();

        if (!(strlen((string) $postalCodeFormat) === strlen((string) $value))) {
            $this->context->buildViolation(sprintf('Please enter a valid postal code. It must have this form %s.', $postalCodeFormat))
                ->addViolation();
        }
    }
}
