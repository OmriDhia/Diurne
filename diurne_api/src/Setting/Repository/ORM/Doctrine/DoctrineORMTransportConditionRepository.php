<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TransportCondition;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTransportConditionRepository extends DoctrineORMRepository implements TransportConditionRepository
{
    protected const ENTITY_CLASS = TransportCondition::class;
    protected const ALIAS = 'transportCondition';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TransportCondition $transportCondition, $flush = false): void
    {
        $this->manager->persist($transportCondition);

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
    /**
     * @return null|object
     */
    public function findRandomTransportCondition()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT id FROM transport_condition ORDER BY RAND() LIMIT 1';
        $stmt = $conn->executeQuery($sql);
        $randomId = $stmt->fetchOne();

        return $randomId ? $this->find($randomId) : null;
    }
}
