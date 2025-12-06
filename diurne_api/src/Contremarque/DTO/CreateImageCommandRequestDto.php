<?php

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateImageCommandRequestDto extends BaseDto
{
    #[Assert\Type('string')]
    public string $commandNumber;
    #[Assert\Type('string')]
    public ?string $commercialComment = null;
    #[Assert\Type('string')]
    public ?string $advComment = null;
    #[Assert\Type('string')]
    public ?string $rn = null;
    #[Assert\Type('string')]
    public ?string $studioComment = null;

    #[Assert\Type('string')]
    public ?string $objectType = null;
    #[Assert\Type('integer')]
    public ?int $objectId = null;
    #[Assert\Type('integer')]
    public ?int $status_id = null;
}
