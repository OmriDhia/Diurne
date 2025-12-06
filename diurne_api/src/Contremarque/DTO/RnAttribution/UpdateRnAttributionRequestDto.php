<?php

namespace App\Contremarque\DTO\RnAttribution;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateRnAttributionRequestDto extends BaseDto
{
    /**
     * @param string|null $rn
     * @param string|null $attributedAt
     * @param string|null $canceledAt
     */
    public function __construct(
        #[Assert\Length(max: 50)]
        public readonly ?string $rn = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $attributedAt = null,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $canceledAt = null
    )
    {
    }
}