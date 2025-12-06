<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetUsersQueryDto
{
    #[Assert\Valid]
    public ?GetUsersFilterDto $filter = null;
    #[Assert\LessThanOrEqual(500)]
    public ?int $page = null;
    #[Assert\LessThanOrEqual(100)]
    public ?int $itemPerPage = null;
}
