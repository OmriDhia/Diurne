<?php

namespace App\Setting\Repository\ORM\Doctrine;

use InvalidArgumentException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Quality;
use App\Setting\Entity\TarifTexture;
use App\Setting\Repository\QualityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMQualityRepository extends DoctrineORMRepository implements QualityRepository
{
    protected const ENTITY_CLASS = Quality::class;
    protected const ALIAS = 'quality';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Quality $quality): void
    {
        $this->persist($quality);
        $this->flush();
    }

    public function create(array $data): Quality
    {
        $quality = new Quality();
        $quality->setName($data['name']);
        $quality->setWeight($data['weight'] ?? null);
        $quality->setVelvetStandard($data['velvet_standard'] ?? null);

        $this->save($quality);

        return $quality;
    }

    /**
     * @return Quality
     */
    public function update($entity, array $data)
    {
        if (!$entity instanceof Quality) {
            throw new InvalidArgumentException('Entity must be an instance of Quality.');
        }

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'name':
                    $entity->setName($value);
                    break;
                case 'weight':
                    $entity->setWeight($value);
                    break;
                case 'velvet_standard':
                    $entity->setVelvetStandard($value);
                    break;
                    // Add more cases for other properties as needed
                default:
                    // Handle unknown fields or ignore them
                    break;
            }
        }

        $this->persist($entity);
        $this->flush();

        return $entity;
    }

    /**
     * Find a random Quality entity.
     */
    public function findRandomQuality(): ?Quality
    {
        // Get the total count of Quality records
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(quality.id)')
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

        // Fetch a random Quality entity based on the random offset
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

    public function findQualityByName(string $name): ?Quality
    {
        return $this->manager->createQueryBuilder()
            ->select('q')
            ->from(self::ENTITY_CLASS, 'q')
            ->where('q.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getRandomQualityWithTarifTexture(TarifTexture $tarifTexture): ?Quality
    {
        // Récupérer les IDs des `Quality` associés à ce `TarifTexture`
        $qualityIds = $this->manager->createQueryBuilder()
            ->select('q.id')
            ->from(Quality::class, 'q')
            ->join('q.qualityTarifTextures', 'qt')
            ->where('qt.tarifTexture = :tarifTexture')
            ->setParameter('tarifTexture', $tarifTexture)
            ->getQuery()
            ->getArrayResult();

        if (empty($qualityIds)) {
            return null; // Aucun `Quality` trouvé
        }

        // Sélectionner un ID aléatoire
        $randomId = $qualityIds[array_rand($qualityIds)]['id'];

        // Retourner l'entité `Quality` correspondante
        return $this->manager->createQueryBuilder()
            ->select('q')
            ->from(Quality::class, 'q')
            ->where('q.id = :id')
            ->setParameter('id', $randomId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
