<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\CommandResponse;
use App\Contact\Entity\ContactCommercialHistory;

final readonly class CommercialAttributionValidationResponse implements CommandResponse
{
    public function __construct(
        private ContactCommercialHistory $contactCommercialHistory
    ) {
    }

    /**
     * @return (int|null|string)[]
     *
     * @psalm-return array{customerId: int|null, commercialId: int|null, status: null|string, from: string, to: string}
     */
    public function toArray(): array
    {
        return [
            'customerId' => $this->contactCommercialHistory->getCustomer()->getId(),
            'commercialId' => $this->contactCommercialHistory->getCommercial()->getId(),
            'status' => $this->contactCommercialHistory->getStatus()->getName(),
            'from' => $this->contactCommercialHistory->getFromDate()->format('Y-m-d H:i:s'),
            'to' => !empty($this->contactCommercialHistory->getToDate()) ? $this->contactCommercialHistory->getToDate()->format('Y-m-d H:i:s') : '',
        ];
    }
}
