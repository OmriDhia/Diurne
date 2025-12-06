<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use DateTimeInterface;
use InvalidArgumentException;
use App\Common\Bus\Command\Command;

final readonly class AssignIntermediaryToCustomerCommand implements Command
{
    public function __construct(
        public int $intermediaryId,
        public int $customerId,
        public int $intermediaryTypeId,
        public ?DateTimeInterface $fromDate,
        public ?DateTimeInterface $toDate = null,
    ) {
        if ($intermediaryId <= 0) {
            throw new InvalidArgumentException('Intermediary ID must be greater than 0.');
        }
        if ($customerId <= 0) {
            throw new InvalidArgumentException('Customer ID must be greater than 0.');
        }
        if ($intermediaryTypeId <= 0) {
            throw new InvalidArgumentException('Intermediary type ID must be greater than 0.');
        }
        if ($fromDate === null) {
            throw new InvalidArgumentException('From date cannot be null.');
        }
        if ($toDate !== null && $toDate < $fromDate) {
            throw new InvalidArgumentException('To date cannot be earlier than from date.');
        }
    }
}
