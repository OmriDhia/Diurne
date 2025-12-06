<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\DiStatus;
use App\Contremarque\Repository\DiStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMDiStatusRepository extends DoctrineORMRepository implements DiStatusRepository
{
    protected const ENTITY_CLASS = DiStatus::class;
    protected const ALIAS = 'di_status';

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
}
