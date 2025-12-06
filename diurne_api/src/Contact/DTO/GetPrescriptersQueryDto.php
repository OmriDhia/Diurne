<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetPrescriptersQueryDto
{
    #[Assert\LessThanOrEqual(500)]
    public ?int $page = null;
    #[Assert\LessThanOrEqual(100)]
    public ?int $itemPerPage = null;
}
