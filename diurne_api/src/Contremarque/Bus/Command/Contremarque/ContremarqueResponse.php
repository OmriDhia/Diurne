<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use DateTimeImmutable;
use DateTimeInterface;
use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Contremarque;

final class ContremarqueResponse implements CommandResponse
{
    public function __construct(
        public Contremarque $contremarque
    ) {
    }

    /**
     * @return (DateTimeImmutable|DateTimeInterface|bool|int|mixed|null|string)[]
     *
     * @psalm-return array{contremarque_id: int|null, projectNumber: null|string, designation: null|string, destination_location: null|string, target_date: DateTimeInterface|null, customer: ''|mixed, customerDiscount: ''|mixed, prescriber: ''|mixed, commission: null|string, commission_on_deposit: bool|null, commercials: mixed, createdAt: DateTimeImmutable|null, updatedAt: DateTimeInterface|null}
     */
    public function toArray(): array
    {
        return $this->contremarque->toArray();
    }
}
