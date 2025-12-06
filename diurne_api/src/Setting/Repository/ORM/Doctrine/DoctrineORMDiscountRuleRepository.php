<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\DiscountRule;
use App\Setting\Repository\DiscountRuleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMDiscountRuleRepository extends DoctrineORMRepository implements DiscountRuleRepository
{
    protected const ENTITY_CLASS = DiscountRule::class;
    protected const ALIAS = 'discountRule';

    /**
     * DoctrineORMDiscountRuleRepository constructor.
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

    public function selectRandomDiscountRule(): object|null
    {
        $sql = 'SELECT d.id FROM discount_rule d ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return $this->findOneBy(['discount' => 20.00]);
        }

        return $this->find((int) $id);
    }
}
