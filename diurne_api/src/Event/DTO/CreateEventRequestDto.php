<?php

declare(strict_types=1);

namespace App\Event\DTO;

use App\Common\Assert as CommonAsserts;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateEventRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'nomenclatureId cannot be empty.')]
        #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
        public ?int $nomenclatureId,

        #[Assert\NotBlank(message: 'customerId cannot be empty.')]
        #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
        public ?int $customerId,

        #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
        public ?int $quoteId,

        #[Assert\Type(type: 'integer', message: 'The value {{ value }} is not a valid {{ type }}.')]
        public ?int $contremarqueId,

        #[Assert\AtLeastOneOf([
            new CommonAsserts\Date(message: 'Please enter a valid date.'),
            new Assert\Blank(), // Permet d'accepter ""
        ])]
        public ?string $next_reminder_deadline,

        #[Assert\NotBlank(message: 'Event date cannot be empty.')]
        #[CommonAsserts\Date(message: 'Please enter a valid date.')]
        public ?string $event_date,

        #[Assert\Length(max: 5000, maxMessage: 'Commentaire cannot exceed {{ limit }} characters.')]
        public ?string $commentaire,

        public ?bool $reminder_disabled,

        #[Assert\Valid]
        public ?PeoplePresentDto $people_present,
    ) {}
}
