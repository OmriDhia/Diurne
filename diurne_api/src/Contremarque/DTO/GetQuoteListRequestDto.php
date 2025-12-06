<?php

namespace App\Contremarque\DTO;

class GetQuoteListRequestDto
{
    public function __construct(public ?int $quoteId = null, public ?string $devis = null, public ?string $contremarque = null, public ?int $contremarqueId = null, public ?int $locationId = null, public ?string $customer = null, public ?string $commercial = null, public ?string $creationDate = null, public ?string $validationDate = null, public ?int $page = null, public ?int $itemsPerPage = null, public ?int $limit = null, public ?string $orderBy = null, public ?string $orderWay = null, public ?bool $isValidated = null)
    {
    }
}
