<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetSpecificTreatmentRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $treatmentId;
}
