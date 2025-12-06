<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerInstructionRequestDto
{
    #[Assert\Type('string')]
    public ?string $objectType = null;
    #[Assert\Type('integer')]
    public ?int $objectId = null;

    public function __construct(#[Assert\Length(
        max: 50,
        maxMessage: 'The order number must not exceed {{ limit }} characters.'
    )]
                                public ?string $orderNumber = null, #[Assert\Length(
        max: 50,
        maxMessage: 'The transmission advice must not exceed {{ limit }} characters.'
    )]
                                public ?string $transmissionAdvice = null, #[Assert\Length(
        max: 255,
        maxMessage: 'The customer comment must not exceed {{ limit }} characters.'
    )]
                                public ?string $customerComment = null, #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}-\d{2}$/',
        message: "The customer validation date must be in the format 'YYYY-MM-DD'."
    )]
                                public ?string $customerValidationDate = null, public ?bool $hasConstraints = null, public ?bool $hasValidateSample = null, public ?bool $hasFinitionInstruction = null, #[Assert\Type(
        type: 'integer',
        message: 'The validated sample ID must be an integer.'
    )]
                                public ?int    $validatedSampleId = null, #[Assert\Type(
        type: 'integer',
        message: 'The finition instruction ID must be an integer.'
    )]
                                public ?int    $finitionInstructionId = null, #[Assert\Type(
        type: 'integer',
        message: 'The constraint instruction ID must be an integer.'
    )]
                                public ?int    $constraintInstructionId = null)
    {
    }
}
