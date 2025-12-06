<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TaxRule;
use App\Setting\Repository\TaxRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTaxRuleRepository extends DoctrineORMRepository implements TaxRuleRepository
{
    protected const ENTITY_CLASS = TaxRule::class;
    protected const ALIAS = 'taxRule';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TaxRule $taxRule, $flush = false): void
    {
        $this->manager->persist($taxRule);

        if ($flush) {
            $this->manager->flush();
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

    public function findRandomTaxRule()
    {
        // Get the total count of statuses
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(taxRule.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No statuses available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random status
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
