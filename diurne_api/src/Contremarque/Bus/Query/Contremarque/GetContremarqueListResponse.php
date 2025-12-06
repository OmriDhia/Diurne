<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Contremarque;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Repository\ContremarqueRepository;

/**
 * This class represents the response data for a query to retrieve contremarques with pagination and total count.
 * It includes the total count of contremarques, current page number, items per page, and an array of contremarques.
 */
final class GetContremarqueListResponse implements QueryResponse
{
    private ContremarqueRepository $contremarqueRepository;

    /**
     * Constructor for GetContremarqueListResponse.
     *
     * @param int   $count         total count of contremarques matching the query
     * @param int   $page          current page number
     * @param int   $itemsPerPage  number of items per page
     * @param array $contremarques array of contremarque entities
     */
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $contremarques
    ) {
    }

    /**
     * Converts the response object to an array representation.
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'contremarques' => $this->transformContremarques($this->contremarques),
        ];
    }

    /**
     * Transforms the contremarque data.
     *
     * @param array $contremarques array of contremarque data
     *
     * @return array transformed array of contremarques
     */
    private function transformContremarques(array $contremarques): array
    {
        return array_map(function ($contremarqueData) {
            $customerName = $contremarqueData['customer_name'] ?? null;
            $commercialName = $contremarqueData['commercial_name'] ?? null;
            $lastEvent = [
                'latest_event_subject' => $contremarqueData['latest_event_subject'] ?? null,
                'id' => $contremarqueData['id'] ?? null,
                'nomenclature_id' => $contremarqueData['nomenclature_id'] ?? null,
                'customer_id' => $contremarqueData['customer_id'] ?? null,
                'next_reminder_deadline' => $contremarqueData['next_reminder_deadline'] ?? null,
                'reminder_disabled' => $contremarqueData['reminder_disabled'] ?? null,
                'commentaire' => $contremarqueData['commentaire'] ?? null,
                'people_present' => $contremarqueData['people_present'] ?? null,
                'event_date' => $contremarqueData['event_date'] ?? null,
                'created_at' => $contremarqueData['created_at'] ?? null,
                'updated_at' => $contremarqueData['updated_at'] ?? null,
                'contremarque_id' => $contremarqueData['contremarque_id'] ?? null,
                'subject' => $contremarqueData['subject'] ?? null,
            ];

            $contremarque = $this->contremarqueRepository->find((int) $contremarqueData['id_0']);

            return array_merge([
                'customer_name' => $customerName,
                'commercial_name' => $commercialName,
                'last_event' => $lastEvent,
            ], $contremarque ? $contremarque->toArray() : []);
        }, $contremarques);
    }

    /**
     * Sets the contremarque repository.
     */
    public function setContremarqueRepository(ContremarqueRepository $contremarqueRepository): void
    {
        $this->contremarqueRepository = $contremarqueRepository;
    }
}
