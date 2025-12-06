<?php

namespace App\Contremarque\DTO;

use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Validator\Constraints as Assert;

#[MapQueryString]
class GetAllSamplesByCarpetDesignOrderRequestDto
{
    public function __construct(private readonly ?int $page = 1, private readonly ?int $itemsPerPage = 10, private readonly ?string $orderBy = 'id', private readonly ?string $orderWay = 'DESC', private readonly ?int $carpetDesignOrderId = null)
    {
    }

    #[Assert\PositiveOrZero(message: 'Page must be a positive integer or zero')]
    public function getPage(): ?int
    {
        return $this->page;
    }

    #[Assert\Positive(message: 'Items per page must be a positive integer')]
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: 'Items per page must be between 1 and 100'
    )]
    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    #[Assert\Choice(callback: [\App\Contremarque\DTO\GetAllSamplesByCarpetDesignOrderRequestDto::class, 'getValidOrderByColumns'], message: 'Invalid order by column')]
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'Order way must be ASC or DESC')]
    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    #[Assert\PositiveOrZero(message: 'Carpet Design Order ID must be a positive integer or zero')]
    public function getCarpetDesignOrderId(): ?int
    {
        return $this->carpetDesignOrderId;
    }

    public static function getValidOrderByColumns(): array
    {
        return ['id', 'diCommandNumber', 'createdAt', 'updatedAt'];
    }
}
