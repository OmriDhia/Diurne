<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Police;
use App\Setting\Repository\PoliceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMPoliceRepository extends DoctrineORMRepository implements PoliceRepository
{
    protected const ENTITY_CLASS = Police::class;
    protected const ALIAS = 'police';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByLabel(string $label): ?Police
    {
        return $this->findOneBy(['label' => $label]);
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
}
