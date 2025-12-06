<?php

namespace App\Contremarque\DTO;

class GetContremarqueListRequestDto
{
    public function __construct(public ?string $designation, public ?int $customerId, public ?int $contremarqueId, public ?int $commercialId, public ?string $creationDate, public ?string $targetDate, public ?int $page, public ?int $itemsPerPage, public ?int $limit, public ?string $order, public ?string $orderWay, public ?string $customerName, public ?string $commercial, public ?string $prescripteur, public ?bool $relaunchExceeded, public ?bool $relaunchExceededByWeek, public ?bool $withoutRelaunch, public ?bool $isCurrentProject)
    {
    }
}
