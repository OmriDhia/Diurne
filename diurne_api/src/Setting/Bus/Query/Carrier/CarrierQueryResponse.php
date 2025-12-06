<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Carrier;

class CarrierQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|Carrier> $carriers
     */
    public function __construct(private readonly array $carriers, private readonly int $totalItems, private readonly int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $response = [];
        foreach ($this->carriers as $carrier) {
            if ($carrier instanceof Carrier) {
                $response[] = [
                    'id' => $carrier->getId(),
                    'name' => $carrier->getName(),
                    'contact' => $carrier->getContact(),
                    'email' => $carrier->getEmail(),
                    'phone' => $carrier->getPhone(),
                    'fax' => $carrier->getFax(),
                    'address' => $carrier->getAddress(),
                    'createdAt' => $carrier->getCreatedAt() ? $carrier->getCreatedAt()->format('Y-m-d H:i:s') : null,
                    'updatedAt' => $carrier->getUpdatedAt() ? $carrier->getUpdatedAt()->format('Y-m-d H:i:s') : null,
                ];
            } else {
                $response[] = $carrier; // Already an array from cache
            }
        }

        return [
            'data' => $response,
            'pagination' => [
                'totalItems' => $this->totalItems,
                'totalPages' => ceil($this->totalItems / $this->itemsPerPage),
                'currentPage' => $this->page,
                'itemsPerPage' => $this->itemsPerPage,
            ]
        ];
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
