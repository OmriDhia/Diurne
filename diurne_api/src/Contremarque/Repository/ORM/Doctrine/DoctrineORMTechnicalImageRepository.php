<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\ImageCommand\TechnicalImage;
use App\Contremarque\Repository\TechnicalImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTechnicalImageRepository extends DoctrineORMRepository implements TechnicalImageRepository
{
    protected const ENTITY_CLASS = TechnicalImage::class;
    protected const ALIAS = 'technicalImage';

    public function __construct(
        EntityManagerInterface $manager,
    )
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