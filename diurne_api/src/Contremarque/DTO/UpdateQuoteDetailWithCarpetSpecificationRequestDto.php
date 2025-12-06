<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateQuoteDetailWithCarpetSpecificationRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Valid]
    public UpdateQuoteDetailRequestDto $quoteDetail;

    #[Assert\NotBlank]
    public UpdateCarpetSpecificationDTO $carpetSpecification;
}
