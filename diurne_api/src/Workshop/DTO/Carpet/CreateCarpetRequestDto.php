<?php

declare(strict_types=1);

namespace App\Workshop\DTO\Carpet;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetRequestDto
{
    /**
     * @param int $manufacturerId
     * @param int|null $imageCommandId
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int  $manufacturerId,
        #[Assert\Positive]
        public readonly ?int $imageCommandId = null
    )
    {
    }
}