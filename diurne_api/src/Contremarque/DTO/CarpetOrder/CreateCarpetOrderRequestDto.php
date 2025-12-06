<?php
declare(strict_types=1);

namespace App\Contremarque\DTO\CarpetOrder;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetOrderRequestDto extends BaseDto
{
    /**
     * @param int $originalQuoteId
     * @param int $clonedQuoteId
     * @param int $contremarqueId
     * @param string|null $createdAt
     */
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $originalQuoteId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $clonedQuoteId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int     $contremarqueId,

        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        public readonly ?string $createdAt = null,


    )
    {
    }
}