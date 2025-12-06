<?php

declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopInformation;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatePricesRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $idWorkshopInformation;
}
