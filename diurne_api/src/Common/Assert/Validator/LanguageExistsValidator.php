<?php

declare(strict_types=1);

namespace App\Common\Assert\Validator;

use App\Common\Assert\LanguageExists;
use App\Setting\Repository\LanguageRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LanguageExistsValidator extends ConstraintValidator
{
    public function __construct(private readonly LanguageRepository $languageRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof LanguageExists) {
            throw new UnexpectedTypeException($constraint, LanguageExists::class);
        }

        if (null === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        // Check if the language exists
        // Check if the language exists in the database
        if (!$this->languageRepository->find($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', (string) $value) // ✅ Cast integer to string
                ->addViolation();
        }
    }
}
