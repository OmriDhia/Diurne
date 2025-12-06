<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Common\Bus\Query\QueryHandler;
use App\Setting\Entity\Carrier;
use App\Setting\Repository\CarrierRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetAllCarrierQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(
        private readonly CarrierRepository $carrierRepository
    ) {}

    public function __invoke(GetAllCarrierQuery $query): CarrierQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'carriers_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $carriersData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapCarriers(),
                3600
            );

            $totalItems = count($carriersData);
        } else {
            $carriersData = $this->carrierRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->carrierRepository->count([]);
        }

        return new CarrierQueryResponse(
            $carriersData,
            $totalItems,
            $query->getPage() ?? 1,
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all Carrier entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapCarriers(): array
    {
        $carriers = $this->carrierRepository->findAll();
        $carriersData = [];

        foreach ($carriers as $carrier) {
            $carriersData[] = $this->mapCarrierToArray($carrier);
        }

        return $carriersData;
    }

    /**
     * Maps a Carrier entity to an array.
     *
     * @param Carrier $carrier
     * @return array<string, mixed>
     */
    private function mapCarrierToArray(Carrier $carrier): array
    {
        return [
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
    }
}
