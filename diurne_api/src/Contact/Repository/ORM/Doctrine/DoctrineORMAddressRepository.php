<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\Address;
use App\Contact\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMAddressRepository extends DoctrineORMRepository implements AddressRepository
{
    protected const ENTITY_CLASS = Address::class;
    protected const ALIAS = 'address';

    /**
     * DoctrineORMAddressRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return Address
     */
    public function create(array $data)
    {
        $address = new Address();
        $address->setFullName($data['fullName']);
        $address->setAddress1($data['address1']);
        $address->setCity($data['city']);
        $address->setState($data['state'] ?? null);
        $address->setComment($data['comment'] ?? null);
        $address->setCountry($data['country'] ?? null);
        $address->setAddressType($data['addressType'] ?? null);
        $address->setIsFValide($data['is_f_valide'] ?? true);
        $address->setIsLValide($data['is_l_valide'] ?? true);
        $address->setZipCode($data['zip_code'] ?? null);
        $address->setIsWrong($data['is_wrong'] ?? false);
        $this->persist($address);
        $this->flush();

        return $address;
    }

    /**
     * @param object $address
     *
     * @return object
     */
    public function update($address, array $data)
    {
        $address->setFullName($data['fullName']);
        $address->setAddress1($data['address1']);
        $address->setCity($data['city']);
        $address->setState($data['state'] ?? null);
        $address->setComment($data['comment'] ?? null);
        $address->setCountry($data['country'] ?? null);
        $address->setAddressType($data['addressType'] ?? null);
        $address->setIsFValide($data['is_f_valide'] ?? true);
        $address->setIsLValide($data['is_l_valide'] ?? true);
        $address->setZipCode($data['zip_code'] ?? null);
        $address->setIsWrong($data['is_wrong'] ?? false);
        $this->persist($address);
        $this->flush();

        return $address;
    }

    /**
     * @return null|object
     */
    public function getDeliveryAddress($customer)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT a.id 
        FROM customer c
        INNER JOIN customer_address ca ON ca.customer_id = c.id
        LEFT JOIN address a ON a.id = ca.address_id
        LEFT JOIN address_type aty ON aty.id = a.address_type_id
        WHERE aty.name = :addressTypeName AND c.id = :customerId
    ';

        $params = [
            'addressTypeName' => 'Livraison',
            'customerId' => $customer->getId(),
        ];

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery($params);

        $id_address = (int)$res->fetchOne();

        return $this->find($id_address);
    }

    /**
     * @return null|object
     */
    public function getRandomAddress($customer)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT a.id 
            FROM customer c
            INNER JOIN customer_address ca ON ca.customer_id = c.id
            LEFT JOIN address a ON a.id = ca.address_id
            LEFT JOIN address_type aty ON aty.id = a.address_type_id
            WHERE c.id = :customerId
        ';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery([
            'customerId' => $customer->getId(),
        ]);

        // Fetch the result and return true if a row is found, false otherwise
        $id_address = (int)$res->fetchOne();

        return $this->find($id_address);
    }

    /**
     * @return null|object
     */
    public function getInvoiceAddress($customer)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT a.id 
            FROM customer c
            INNER JOIN customer_address ca ON ca.customer_id = c.id
            LEFT JOIN address a ON a.id = ca.address_id
            LEFT JOIN address_type aty ON aty.id = a.address_type_id
            WHERE aty.name = :addressTypeName AND c.id = :customerId
        ';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery([
            'addressTypeName' => 'Facturation',
            'customerId' => $customer->getId(),
        ]);

        // Fetch the result and return true if a row is found, false otherwise
        $id_address = (int)$res->fetchOne();

        return $this->find($id_address);
    }
}
