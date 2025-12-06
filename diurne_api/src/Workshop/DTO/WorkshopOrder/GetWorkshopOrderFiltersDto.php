<?php

namespace App\Workshop\DTO\WorkshopOrder;

use Symfony\Component\Validator\Constraints as Assert;

class GetWorkshopOrderFiltersDto
{
    public ?int $customer = null;
}