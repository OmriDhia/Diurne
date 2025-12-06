<?php

declare(strict_types=1);

namespace App\Common\Assert\Validator;

use App\Common\Assert\IsLastnameRequired;
use App\Contact\DTO\CreateCustomerRequestDto;
use App\Setting\Entity\CustomerGroup;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use App\Contact\Repository\CustomerGroupRepository as RepositoryCustomerGroupRepository;

class IsLastnameRequiredValidator extends ConstraintValidator
{
    public function __construct(private readonly RepositoryCustomerGroupRepository $customerGroupRepository) {}
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsLastnameRequired) {
            throw new UnexpectedTypeException($constraint, IsLastnameRequired::class);
        }

        /** @var object $object */
        $object = $this->context->getObject();
        if (!property_exists($object, 'customerGroupId')) {
            return; // Skip validation if `customerGroupId` is missing
        }

        $customerGroupId = $object->customerGroupId;
        $customerGroup = $this->customerGroupRepository->find($customerGroupId);

        if ($customerGroup && $customerGroup->getName() == 'Particulier (Client)' && empty($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
