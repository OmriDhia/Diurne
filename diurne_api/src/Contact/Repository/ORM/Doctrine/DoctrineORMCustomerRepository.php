<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use DomainException;
use App\Contact\Entity\Address;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\Customer;
use App\Contact\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use App\Contact\Entity\CarpetDesignOrder;
use App\Contact\Entity\Contact;
use App\Contremarque\Entity\CarpetDesignOrder as EntityCarpetDesignOrder;
use App\User\Entity\User;

class DoctrineORMCustomerRepository extends DoctrineORMRepository implements CustomerRepository
{
    protected const ENTITY_CLASS = Customer::class;
    protected const ALIAS = 'customer';

    /**
     * DoctrineORMCustomerRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
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

    public function findOneByCode($code)
    {
        try {
            $object = $this->query()
                ->where('customer.code = :code')
                ->setParameter('code', $code)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    public function findOneById($id)
    {
        try {
            $object = $this->query()
                ->where('customer.id = :id')
                ->setParameter('id', $id)
                ->getQuery()->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException) {
            throw new DomainException('More than one result found');
        }

        return $object;
    }

    public function getRandomCustomer(): ?Customer
    {
        $count = $this->query()
            ->select('COUNT(customer.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomOffset = random_int(0, max(0, $count - 1));

        return $this->query()
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function alreadyHasPostalAddress($customerId): bool
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 1 
            FROM customer c
            INNER JOIN customer_address ca ON ca.customer_id = c.id
            LEFT JOIN address a ON a.id = ca.address_id
            LEFT JOIN address_type at ON at.id = a.address_type_id
            WHERE at.name = :addressTypeName AND c.id = :customerId
        ';

        $stmt = $conn->prepare($sql);
        $res = $stmt->executeQuery([
            'addressTypeName' => 'Postale (Mailing)',
            'customerId' => $customerId,
        ]);

        // Fetch the result and return true if a row is found, false otherwise
        return (bool) $res->fetchOne();
    }

    /**
     * @return false|null|object
     */
    public function selectRandomAgent(): object|false|null
    {
        $sql = "SELECT c.id FROM customer c
          INNER JOIN intermediary_information_sheet iis ON(iis.id=c.intermediary_information_sheet_id)
          INNER JOIN intermediary_type t ON(t.id=iis.intermediary_type_id)
          WHERE t.name='Agent'
          ORDER BY RAND() ASC LIMIT 1";
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }

    /**
     * @return false|null|object
     */
    public function selectRandomPrescripteur(): object|false|null
    {
        $sql = "SELECT c.id FROM customer c
          INNER JOIN intermediary_information_sheet iis ON(iis.id=c.intermediary_information_sheet_id)
          INNER JOIN intermediary_type t ON(t.id=iis.intermediary_type_id)
          WHERE t.name='Prescripteur'
          ORDER BY RAND() ASC LIMIT 1";
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }
    public function findCommercialByCarpetDesignOrder(EntityCarpetDesignOrder $carpetDesignOrder): ?User
    {
        $customer = $carpetDesignOrder->getProjectDi()->getContremarque()->getCustomer();
        $sql = "SELECT cch.commercial_id FROM customer c
          INNER JOIN contact_commercial_history cch ON(cch.customer_id=c.id) 
          WHERE c.id = :customerId
          ORDER BY from_date DESC LIMIT 1";
        $stmt = $this->manager->getConnection()->prepare($sql);
        $stmt->bindValue('customerId', $customer->getId());
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return null;
        }

        return $this->manager->getRepository(User::class)->find((int) $id);
    }
    private function getFirstContactNameByCustomerId(int $customerId): ?string
    {
        $customer = $this->find($customerId);
        $queryBuilder = $this->manager->createQueryBuilder()
            ->select('c')
            ->from(Contact::class, 'c')
            ->leftJoin('c.contactInformationSheet', 'cis')
            ->where('c.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('c.created_at', 'ASC') // Assuming createdAt will give us the first contact
            ->setMaxResults(1); // We only want the first contact

        $contact = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$contact || !$contact->getContactInformationSheet()) {
            return null; // No contact information found
        }

        $contactInfo = $contact->getContactInformationSheet();

        // Combine first and last names
        return $contactInfo->getFirstname() . ' ' . $contactInfo->getLastname();
    }

    /**
     * @param int $customerId
     * @return string The name of the first contact linked to the given customer, or 'No contact found' if none found
     */
    public function getContactName(int $customerId): string
    {
        $contactName = $this->getFirstContactNameByCustomerId($customerId);

        return $contactName ?: 'No contact found'; // Return a default message if no contact found
    }
    public function getCustomerAddress(int $customerId, string $addressType): ?string
    {
        $conn = $this->getEntityManager()->getConnection();

        // SQL query to fetch the customer address by the customer ID and address type
        $sql = '
        SELECT a.address1, a.city, a.zip_code, a.state, a.country_id, at.name as address_type
        FROM address a
        INNER JOIN customer_address ca ON a.id = ca.address_id
        INNER JOIN customer c ON ca.customer_id = c.id
        INNER JOIN address_type at ON a.address_type_id = at.id
        WHERE c.id = :customerId AND at.name = :addressType
        LIMIT 1
    ';

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('customerId', $customerId);
        $stmt->bindValue('addressType', $addressType);
        $result = $stmt->executeQuery()->fetchAssociative();

        if (!$result) {
            return null; // Return null if no address is found
        }


        $state = $result['state'] ?: ''; // Use state if exists

        return sprintf(
            "%s %s %s, %s %s", // Format the address
            $result['address1'], // Address1
            $result['city'], // City
            $result['zip_code'], // Zip Code
            $state, // State if exists
            $this->getCountryNameById($result['country_id']) // Country Name (assuming this method exists)
        );
    }
    private function getCountryNameById(int $countryId): string
    {
        // You can implement this method to fetch the country name by its ID from the database
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT name FROM country WHERE id = :countryId';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('countryId', $countryId);
        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ? $result['name'] : 'Unknown';
    }
}
