<?php

namespace App\Event\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PeoplePresentDto
{
    #[Assert\All([
        new Assert\Type(type: 'integer', message: 'Each person ID must be an integer.'),
    ])]
    #[Assert\Type(type: 'array')] // Ensures it's an array
    public array $contacts = [];

    #[Assert\All([
        new Assert\Type(type: 'integer', message: 'Each person ID must be an integer.'),
    ])]
    #[Assert\Type(type: 'array')]
    public array $users = [];
    public function toArray(): array
    {
        return [
            'contacts' => $this->contacts,
            'users' => $this->users,
        ];
    }
}
