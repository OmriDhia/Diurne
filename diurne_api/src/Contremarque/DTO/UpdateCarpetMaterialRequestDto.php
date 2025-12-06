<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCarpetMaterialRequestDto extends BaseDto
{
    public ?string $rate = null;
    public ?int $materialId = null;
}

