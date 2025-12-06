<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerInstructionRequestDto
{
    #[Assert\Type('string')]
    public ?string $objectType = null;
    #[Assert\Type('integer')]
    public ?int $objectId = null;

    public function __construct(#[Assert\Length(
        max: 50,
        maxMessage: 'The order number must not exceed {{ limit }} characters.'
    )]
                                public ?string $orderNumber, #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
        message: "The transmission advice date must be in the format 'YYYY-MM-DD HH:MM:SS'."
    )]
                                public ?string $transmi_adv, #[Assert\Length(
        max: 255,
        maxMessage: 'The customer comment must not exceed {{ limit }} characters.'
    )]
                                public ?string $customerComment, #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}-\d{2}$/',
        message: "The customer validation date must be in the format 'YYYY-MM-DD'."
    )]
                                public string  $customerValidationDate, public ?bool $hasConstraints = null, public ?bool $hasValidateSample = null, public ?bool $hasFinitionInstruction = null, #[Assert\Type(
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
