<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;

interface ManufacturerPriceGridRepository extends BaseRepository
{
    /**
     * Trouve les grilles par fabricant et groupe tarifaire
     */
    public function findByManufacturerAndTarifGroup(int $manufacturerId, int $tarifGroupId): array;

    /**
     * Récupère les groupes tarifaires disponibles pour un fabricant
     */
    public function findAvailableTarifGroupsForManufacturer(int $manufacturerId): array;

    /**
     * Trouve une grille spécifique par fabricant, qualité et groupe tarifaire
     */
    public function findOneByManufacturerQualityAndTarifGroup(int $manufacturerId, int $qualityId, int $tarifGroupId): ?object;

    /**
     * Récupère l'historique des prix pour un fabricant et une qualité
     */
    public function findPriceHistory(int $manufacturerId, int $qualityId, ?int $limit = null): array;

    /**
     * Désactive toutes les grilles d'un fabricant pour un groupe tarifaire
     */
    public function deactivateAllForManufacturerAndTarifGroup(int $manufacturerId, int $tarifGroupId): int;

    /**
     * Méthode utilitaire pour construire des requêtes complexes
     */
    public function createQueryBuilderWithFilters(array $filters = []): \Doctrine\ORM\QueryBuilder;

    public function findAvailableTarifGroups(): array;
}
