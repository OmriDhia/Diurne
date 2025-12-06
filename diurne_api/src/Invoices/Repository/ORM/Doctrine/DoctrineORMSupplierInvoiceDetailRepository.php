<?php

namespace App\Invoices\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Invoices\Entity\SupplierInvoiceDetail;
use App\Invoices\Repository\SupplierInvoiceDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMSupplierInvoiceDetailRepository extends DoctrineORMRepository implements SupplierInvoiceDetailRepository
{
    protected const ENTITY_CLASS = SupplierInvoiceDetail::class;
    protected const ALIAS = 'supplier_invoice_detail';

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
