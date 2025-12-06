<?php
declare(strict_types=1);

namespace App\Contremarque\DTO\RnAttribution;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRnAttributionRequestDto extends BaseDto
{
    /**
     * @param int $carpetOrderDetailId
     * @param string|null $attributedAt
     * @param string|null $canceledAt
     */
    public function __construct(
        
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $carpetOrderDetailId,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $carpetId,
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $attributedAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $canceledAt = null
    )
    {
    }
}