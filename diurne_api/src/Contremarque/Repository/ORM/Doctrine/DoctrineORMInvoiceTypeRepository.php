<?php

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\InvoiceType;
use App\Contremarque\Repository\InvoiceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMInvoiceTypeRepository extends DoctrineORMRepository implements InvoiceTypeRepository
{
    protected const ENTITY_CLASS = InvoiceType::class;
    protected const ALIAS = 'invoiceType';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        $entity = new InvoiceType();
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
