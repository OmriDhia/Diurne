<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetPriceBase;
use App\Contremarque\Repository\CarpetPriceBaseRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetPriceBaseRepository extends DoctrineORMRepository implements CarpetPriceBaseRepository
{
    protected const ENTITY_CLASS = CarpetPriceBase::class;
    protected const ALIAS = 'carpetPriceBase';

    /**
     * DoctrineORMCarpetPriceBaseRepository constructor.
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
