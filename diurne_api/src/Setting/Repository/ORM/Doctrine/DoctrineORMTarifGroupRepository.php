<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TarifGroup;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTarifGroupRepository extends DoctrineORMRepository implements TarifGroupRepository
{
    protected const ENTITY_CLASS = TarifGroup::class;
    protected const ALIAS = 'tarifGroup';

    /**
     * DoctrineORMTarifGroupRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByYear(string $year): ?TarifGroup
    {
        return $this->findOneBy(['year' => $year]);
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

    public function save(TarifGroup $tarifGroup, $flush = false): void
    {
        $this->manager->persist($tarifGroup);

        if ($flush) {
            $this->manager->flush();
        }
    }
}
