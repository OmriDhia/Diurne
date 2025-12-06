<?php

namespace App\Workshop\DTO\WorkshopRnHistory;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateWorkshopRnHistoryRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly ?int    $eventTypeId = null,

        #[Assert\Positive]
        public readonly ?int    $locationId = null,

        #[Assert\Positive]
        public readonly ?int    $customerId = null,

        #[Assert\Positive]
        public readonly ?int    $workshopOrderId = null,
        #[Assert\Positive]
        public readonly ?int    $carpetId = null,
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $beginAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $endAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $updatedAt = null
    )
    {
    }
}