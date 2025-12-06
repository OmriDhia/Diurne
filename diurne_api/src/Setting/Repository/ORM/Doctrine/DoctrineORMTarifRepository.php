<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\DiscountRule;
use App\Setting\Entity\Tarif;
use App\Setting\Repository\TarifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMTarifRepository extends DoctrineORMRepository implements TarifRepository
{
    protected const ENTITY_CLASS = Tarif::class;
    protected const ALIAS = 'tarif';

    /**
     * DoctrineORMTarifRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
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

    public function getRandomTarif(): ?Tarif
    {
        try {
            // Fetch all IDs of the Tarif entity
            $ids = $this->manager->createQueryBuilder()
                ->select('tarif.id')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getResult();

            if (empty($ids)) {
                return null;
            }

            // Select a random ID
            $randomId = $ids[array_rand($ids)]['id'];

            // Retrieve the Tarif entity by the random ID
            return $this->manager->createQueryBuilder()
                ->select(self::ALIAS)
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->where('tarif.id = :id')
                ->setParameter('id', $randomId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException | NoResultException $e) {
            throw new DomainException('Error fetching random Tarif: ' . $e->getMessage());
        }
    }
    /**
     * Get the last tarif by discount rule.
     *
     * @param DiscountRule $discountRule
     * @return Tarif|null
     */
    public function getLastTarifByDiscountRule(DiscountRule $discountRule)
    {
        return $this->manager->createQueryBuilder()
            ->select('t')
            ->from(self::ENTITY_CLASS, 't')
            ->innerJoin('t.tarifGroup', 'tg')
            ->where('t.discountRule = :discountRule')
            ->setParameter('discountRule', $discountRule)
            ->orderBy('tg.year', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
