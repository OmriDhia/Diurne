<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Carrier;
use App\Setting\Repository\CarrierRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarrierRepository extends DoctrineORMRepository implements CarrierRepository
{
    protected const ENTITY_CLASS = Carrier::class;
    protected const ALIAS = 'carrier';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Carrier $carrier, $flush = false): void
    {
        $this->manager->persist($carrier);

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
