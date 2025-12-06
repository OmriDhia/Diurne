<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetCommercialsQueryDto
{
    #[Assert\Valid]
    public ?GetCommercialsFilterDto $filter = null;
    #[Assert\LessThanOrEqual(500)]
    public ?int $page = null;
    #[Assert\LessThanOrEqual(100)]
    public ?int $itemPerPage = null;
}
