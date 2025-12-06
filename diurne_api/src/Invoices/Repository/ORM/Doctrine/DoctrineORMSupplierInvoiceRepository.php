<?php

namespace App\Invoices\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Invoices\Entity\SupplierInvoice;
use App\Invoices\Repository\SupplierInvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMSupplierInvoiceRepository extends DoctrineORMRepository implements SupplierInvoiceRepository
{
    protected const ENTITY_CLASS = SupplierInvoice::class;
    protected const ALIAS = 'supplierInvoice';
    private const PREFIX = 'F';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data)
    {
        $invoice = new SupplierInvoice();
        $invoiceNumber = $data['invoiceNumber'] ?? null;
        if ($invoiceNumber) {
            $invoice->setInvoiceNumber($invoiceNumber);
            unset($data['invoiceNumber']);
        } else {
            $invoice->setInvoiceNumber($this->getNextInvoiceNumber());
        }

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
                FROM supplier_invoice
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

