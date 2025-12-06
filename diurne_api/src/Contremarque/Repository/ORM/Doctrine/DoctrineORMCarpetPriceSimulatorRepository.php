<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetPriceSimulator;
use App\Contremarque\Repository\CarpetPriceSimulatorRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetPriceSimulatorRepository extends DoctrineORMRepository implements CarpetPriceSimulatorRepository
{
    protected const ENTITY_CLASS = CarpetPriceSimulator::class;
    protected const ALIAS = 'carpetPriceSimulator';

    /**
     * DoctrineORMCarpetPriceSimulatorRepository constructor.
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
}
