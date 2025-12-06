<?php

// src/Contremarque/Event/ContremarqueContactCurrentUpdatedEvent.php

namespace App\Contremarque\Event;

use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\ContremarqueContact;
use Symfony\Contracts\EventDispatcher\Event;

class ContremarqueContactCurrentUpdatedEvent extends Event
{
    public const NAME = 'contremarque.contact.current_updated';

    public function __construct(
        private readonly Contremarque $contremarque,
        private readonly ContremarqueContact $contremarqueContact
    ) {
    }

    public function getContremarque(): Contremarque
    {
        return $this->contremarque;
    }

    public function getContremarqueContact(): ContremarqueContact
    {
        return $this->contremarqueContact;
    }
}
