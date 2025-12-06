<?php

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Material;
use App\Setting\Repository\MaterialRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMMaterialRepository extends DoctrineORMRepository implements MaterialRepository
{

    protected const ENTITY_CLASS = Material::class;
    protected const ALIAS = 'material';

    private readonly Connection $connection;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
        $this->connection = $manager->getConnection(); // Fix: Inject DBAL connection
    }


    /**
     * @return void
     */
    public function create(array $data) {}

    /**
     * @return void
     */
    public function update($entity, array $data) {}
    public function findRandomMaterial(): ?Material
    {
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('COUNT(material.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }

        if ($count === 0) {
            return null;
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve a random material with ORDER BY
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->orderBy(self::ALIAS . '.id', 'ASC') // Fix: Ensure proper row selection
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Get material translations by material IDs and language ISO code.
     */
    public function getMaterialTranslations(array $materialIds, string $isoCode = 'fr'): array
    {
        if (empty($materialIds)) {
            return [];
        }

        $query = $this->connection->executeQuery(
            'SELECT ml.material_id, ml.label 
             FROM material_lang ml
             INNER JOIN language l ON ml.language_id = l.id
             WHERE l.iso_code = :iso AND ml.material_id IN (:ids)',
            ['iso' => $isoCode, 'ids' => $materialIds],
            ['ids' => Connection::PARAM_INT_ARRAY]
        );

        return $query->fetchAllKeyValue(); // Returns [material_id => translated_label]
    }
    /**
     * Return a human-readable string describing a material composition.
     *
     * The given data should contain at least a 'material_id' and a 'rate' key.
     * The function will fetch translations for the given material IDs in the
     * given language ISO code, and format the output as follows:
     *
     *   - <rate>% <material>
     *   - <rate>% <material> et <rate>% <material>
     *
     * If no translations are found, it will fallback to the material reference.
     *
     * @param array $data The data to format
     * @param string $isoCode The language ISO code
     *
     * @return string
     */

    public function getMaterialComposition(array $data, string $isoCode = 'fr'): string
    {
        $materialIds = array_column($data, 'material_id');

        if (empty($materialIds)) {
            return '';
        }

        // Fetch translations from repository
        $translations = $this->getMaterialTranslations($materialIds, $isoCode);

        // Format the output
        $parts = [];
        foreach ($data as $item) {
            $material = $translations[$item['material_id']] ?? strtolower((string) $item['reference']);
            $rate = rtrim((string) $item['rate'], '0'); // Remove trailing zeros
            $rate = rtrim($rate, '.'); // Remove trailing dot if needed
            $parts[] = "{$rate}% {$material}";
        }

        return implode(" et ", $parts);
    }
}
