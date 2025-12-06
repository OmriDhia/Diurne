<?php
declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopRnHistory;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkshopRnHistoryRequestDto extends BaseDto
{

    /**
     * @param int $eventTypeId
     * @param int $locationId
     * @param int $customerId
     * @param int $workshopOrderId
     * @param string $beginAt
     * @param string $endAt
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $eventTypeId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $locationId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $customerId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $workshopOrderId,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $carpetId,
        #[Assert\NotBlank]
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly string  $beginAt,

        #[Assert\NotBlank]
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly string  $endAt,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $createdAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $updatedAt = null
    )
    {
    }
}