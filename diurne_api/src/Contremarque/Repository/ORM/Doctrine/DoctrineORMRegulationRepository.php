<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Regulation;
use App\Contremarque\Repository\RegulationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMRegulationRepository extends DoctrineORMRepository implements RegulationRepository
{
    protected const ENTITY_CLASS = Regulation::class;
    protected const ALIAS = 'regulation';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        $entity = new Regulation();
        $entity->setName($data['name']);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function update($entity, array $data)
    {
        $entity->setName($data['name']);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }
}
