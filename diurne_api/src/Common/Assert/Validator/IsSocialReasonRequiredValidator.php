<?php

declare(strict_types=1);

namespace App\Common\Assert\Validator;

use App\Common\Assert\IsSocialReasonRequired;
use App\Contact\Repository\CustomerGroupRepository as RepositoryCustomerGroupRepository;
use App\Customer\Repository\CustomerGroupRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsSocialReasonRequiredValidator extends ConstraintValidator
{
    public function __construct(private readonly RepositoryCustomerGroupRepository $customerGroupRepository) {}

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsSocialReasonRequired) {
            throw new UnexpectedTypeException($constraint, IsSocialReasonRequired::class);
        }

        /** @var object $object */
        $object = $this->context->getObject();
        if (!property_exists($object, 'customerGroupId')) {
            return; // Skip validation if `customerGroupId` is missing
        }

        $customerGroupId = $object->customerGroupId;
        $customerGroup = $this->customerGroupRepository->find($customerGroupId);

        if ($customerGroup && $customerGroup->getName() !== 'Particulier (Client)' && empty($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
