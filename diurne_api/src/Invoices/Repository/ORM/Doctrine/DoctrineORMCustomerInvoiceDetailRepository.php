<?php

namespace App\Invoices\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Invoices\Entity\CustomerInvoiceDetail;
use App\Invoices\Repository\CustomerInvoiceDetailRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCustomerInvoiceDetailRepository extends DoctrineORMRepository implements CustomerInvoiceDetailRepository
{
    protected const ENTITY_CLASS = CustomerInvoiceDetail::class;
    protected const ALIAS = 'customer_invoice_detail';

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
