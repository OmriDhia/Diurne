<?php

declare(strict_types=1);

namespace App\Contact\DTO;

class GetCommercialsFilterDto
{
    public ?string $firstname = null;
    public ?string $lastname = null;
    public ?string $email = null;
    public ?string $gender = null;
}
