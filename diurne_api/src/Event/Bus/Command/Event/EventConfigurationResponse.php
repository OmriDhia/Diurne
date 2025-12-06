<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\CommandResponse;
use App\Event\Entity\EventNomenclature;

final readonly class EventConfigurationResponse implements CommandResponse
{
    public function __construct(
        private EventNomenclature $eventNomenclature
    ) {
    }

    /**
     * @return (bool|int|null|string)[]
     *
     * @psalm-return array{nomenclature_id: int|null, subject: null|string, is_automatic: bool|null, automatic_followup_delay: int|null}
     */
    public function toArray(): array
    {
        return $this->eventNomenclature->toArray();
    }
}
