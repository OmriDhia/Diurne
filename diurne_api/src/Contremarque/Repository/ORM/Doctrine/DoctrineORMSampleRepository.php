<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Sample;
use App\Contremarque\Repository\SampleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMSampleRepository extends DoctrineORMRepository implements SampleRepository
{
    protected const ENTITY_CLASS = Sample::class;
    protected const ALIAS = 'sample';

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