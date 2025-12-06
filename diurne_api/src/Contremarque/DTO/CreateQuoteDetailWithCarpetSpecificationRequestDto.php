<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateQuoteDetailWithCarpetSpecificationRequestDto extends BaseDto
{
    #[Assert\NotNull]
    #[Assert\Valid] // Ensures that `quoteDetail` is also validated
    public CreateQuoteDetailRequestDto $quoteDetail;

    #[Assert\NotNull]
    public CarpetSpecificationDTO $carpetSpecification;
}
