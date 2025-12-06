<?php

namespace App\Invoices\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Invoices\Entity\CustomerInvoice;
use App\Invoices\Repository\CustomerInvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCustomerInvoiceRepository extends DoctrineORMRepository implements CustomerInvoiceRepository
{
    protected const ENTITY_CLASS = CustomerInvoice::class;
    protected const ALIAS = 'customerInvoice';
    private const PREFIX = 'F';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        $invoice = new CustomerInvoice();
        $invoice->setInvoiceNumber($this->getNextInvoiceNumber());

        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);
            if (method_exists($invoice, $method)) {
                $invoice->$method($value);
            }
        }

        $this->persist($invoice);
        $this->flush();

        return $invoice;
    }

    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    public function getNextInvoiceNumber(): string
    {
        $conn = $this->manager->getConnection();
        $sql = 'SELECT MAX(CAST(SUBSTRING(invoice_number, LENGTH(:prefix) + 1) AS UNSIGNED))
                FROM customer_invoice
                WHERE invoice_number LIKE :pattern';
        $stmt = $conn->executeQuery($sql, [
            'prefix' => self::PREFIX,
            'pattern' => self::PREFIX . '%',
        ]);
        $lastNumber = (int)$stmt->fetchOne();
        $nextNumber = $lastNumber + 1;

        return sprintf('%s%05d', self::PREFIX, $nextNumber);
    }
}

