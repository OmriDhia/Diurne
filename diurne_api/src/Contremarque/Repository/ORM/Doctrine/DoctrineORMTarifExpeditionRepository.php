<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\TarifExpedition;
use App\Contremarque\Repository\TarifExpeditionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTarifExpeditionRepository extends DoctrineORMRepository implements TarifExpeditionRepository
{
    protected const ENTITY_CLASS = TarifExpedition::class;
    protected const ALIAS = 'tarifExpedition';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        $entity = new TarifExpedition();
        $entity->setName($data['name']);
        $entity->setTarif($data['tarif'] ?? null);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function update($entity, array $data)
    {
        $entity->setName($data['name']);
        $entity->setTarif($data['tarif'] ?? null);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }
}
