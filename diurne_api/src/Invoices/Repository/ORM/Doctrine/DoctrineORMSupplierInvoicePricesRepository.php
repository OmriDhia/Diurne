<?php

namespace App\Invoices\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Invoices\Entity\SupplierInvoicePrices;
use App\Invoices\Repository\SupplierInvoicePricesRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMSupplierInvoicePricesRepository extends DoctrineORMRepository implements SupplierInvoicePricesRepository
{
    protected const ENTITY_CLASS = SupplierInvoicePrices::class;
    protected const ALIAS = 'supplierInvoicePrices';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }
}

