<?php

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Quality;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\QualityTarifTextureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMQualityTarifTextureRepository extends DoctrineORMRepository implements QualityTarifTextureRepository
{
    protected const ENTITY_CLASS = QualityTarifTexture::class;
    protected const ALIAS = 'qualityTarifTexture';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(QualityTarifTexture $qualityTarifTexture): void
    {
        $this->persist($qualityTarifTexture);
        $this->flush();
    }

    /**
     * Find a random QualityTarifTexture entity.
     */
    public function findRandomQualityTarifTexture(): ?QualityTarifTexture
    {
        // Get the total count of QualityTarifTexture records

        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(qualityTarifTexture.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No qualities available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Fetch a random QualityTarifTexture entity based on the random offset
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        try {
            return $query->getOneOrNullResult();
        } catch (NoResultException) {
            return null; // No result found
        }
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }
    public function findByQualityAndTarifTexture(Quality $quality, TarifTexture $tarifTexture): ?QualityTarifTexture
    {
        return $this->findOneBy([
            'quality' => $quality,
            'tarifTexture' => $tarifTexture,
        ]);
    }
}
