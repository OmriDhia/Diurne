<?php

declare(strict_types=1);

namespace App\Event\DTO;

use App\Event\DTO\customerAssert\Name;
use App\Common\Assert as addressAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GetEventsFilterDto
{
    public function __construct(
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.', )]
        public ?string $email,
        #[Assert\LessThanOrEqual(500)]
        public ?int $page = null,
        #[Assert\LessThanOrEqual(100)]
        public ?int $itemsPerPage = null,

        #[Assert\Length(max: 50, maxMessage: 'firstname cannot exceed {{ limit }} characters.')]
        public ?string $firstname = null,
        #[Assert\Type(
            type: 'float',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public ?float $tvaCe = null,
        #[addressAssert\Url(message: 'Please enter a valid url.')]
        public ?string $website = null,
        #[Assert\Length(max: 50, maxMessage: 'lastname cannot exceed {{ limit }} characters.')]
        public ?string $lastname = null,
        #[Assert\Length(max: 50, maxMessage: 'commercial cannot exceed {{ limit }} characters.')]
        #[addressAssert\Name(message: 'Please enter a valid name.')]
        public ?string $commercial = null,
        #[Assert\Length(max: 50, maxMessage: 'contact cannot exceed {{ limit }} characters.')]
        #[addressAssert\Name(message: 'Please enter a valid name.')]
        public ?string $contact = null,
        #[Assert\Length(max: 50, maxMessage: 'prescripteur cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public ?string $prescripteur = null,
        public ?bool $active = null,
        public ?bool $hasInvalidCommercial = null,
        public ?bool $onlyLastEvent = null,
        public ?bool $hasOnlyOneContact = null,
        public ?bool $hasNoProject = null,
        public ?bool $hasNextStep = null,
        public ?string $eventDate_from = null,
        public ?string $eventDate_to = null,
        public ?string $next_reminder_deadline_from = null,
        public ?string $next_reminder_deadline_to = null,
        public ?string $subject = null,
        public ?string $socialReason = null,
        #[Assert\Type(
            type: 'string',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public ?string $customerGroups = null,
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public ?int $nomenclatureId = null,
        public ?int $contremarqueId = null,
        public ?int $customerId = null,
        public ?int $quoteId = null,
    ) {
    }
}
