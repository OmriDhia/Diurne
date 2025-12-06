<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\PriceType;
use App\Setting\Repository\PriceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMPriceTypeRepository extends DoctrineORMRepository implements PriceTypeRepository
{
    protected const ENTITY_CLASS = PriceType::class;
    protected const ALIAS = 'priceType';

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
