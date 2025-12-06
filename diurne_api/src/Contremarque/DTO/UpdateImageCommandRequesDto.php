<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateImageCommandRequesDto
{
    #[Assert\Type('string')]
    public ?string $commandNumber = null;
    #[Assert\Type('string')]
    public ?string $commercialComment = null;
    #[Assert\Type('string')]
    public ?string $advComment = null;
    #[Assert\Type('string')]
    public ?string $rn = null;
    #[Assert\Type('string')]
    public ?string $studioComment = null;
    #[Assert\Type('integer')]
    public ?int $status_id = null;
}
