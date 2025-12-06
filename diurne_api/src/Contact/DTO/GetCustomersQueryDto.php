<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetCustomersQueryDto
{
    #[Assert\LessThanOrEqual(500)]
    public ?int $page = 1;
    #[Assert\LessThanOrEqual(300)]
    public ?int $itemsPerPage = 50;
    #[Assert\Valid]
    public ?GetCustomersFilterDto $filter = null;
    public ?string $orderBy = null;
    public ?string $orderWay = null;
    #[Assert\Choice(
        choices: ['csv', 'xls', 'xlsx'],
        message: 'Please select a valid export format (csv, xls, xlsx).'
    )]
    public ?string $exportFormat = null;
}
