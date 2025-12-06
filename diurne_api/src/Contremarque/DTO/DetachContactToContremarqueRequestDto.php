<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DetachContactToContremarqueRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: "Le champ 'contremarqueId' ne doit pas être vide.")]
        #[Assert\Type(type: 'integer', message: "Le champ 'contremarqueId' doit être de type entier.")]
        public int $contremarqueId,

        #[Assert\NotBlank(message: "Le champ 'contactId' ne doit pas être vide.")]
        #[Assert\Type(type: 'integer', message: "Le champ 'contactId' doit être de type entier.")]
        public int $contactId,
    ) {
    }
}
