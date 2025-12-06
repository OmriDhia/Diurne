<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Manufacturer;
use App\Setting\Repository\ManufacturerRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMManufacturerRepository extends DoctrineORMRepository implements ManufacturerRepository
{
    protected const ENTITY_CLASS = Manufacturer::class;
    protected const ALIAS = 'manufacturer';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Manufacturer $manufacturer, $flush = false): void
    {
        $this->manager->persist($manufacturer);

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
