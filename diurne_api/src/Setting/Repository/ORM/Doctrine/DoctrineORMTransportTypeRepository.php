<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TransportType;
use App\Setting\Repository\TransportTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTransportTypeRepository extends DoctrineORMRepository implements TransportTypeRepository
{
    protected const ENTITY_CLASS = TransportType::class;
    protected const ALIAS = 'transportType';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TransportType $transportType, $flush = false): void
    {
        $this->manager->persist($transportType);

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
}
