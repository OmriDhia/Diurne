<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Manufacturer;
use App\Workshop\Entity\Carpet;
use App\Workshop\Repository\CarpetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;


class DoctrineORMCarpetRepository extends DoctrineORMRepository implements CarpetRepository
{
    protected const ENTITY_CLASS = Carpet::class;
    protected const ALIAS = 'carpet';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param Carpet $entity
     * @param bool $flush
     * @return void
     */
    public function save(Carpet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $entity
     * @param array $data
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }


    public function getNextRnNumber(int $manufacturerId): string
    {
        // Get the manufacturer to determine the prefix
        $manufacturer = $this->manager->getRepository(Manufacturer::class)->find($manufacturerId);

        if (!$manufacturer) {
            throw new \InvalidArgumentException('Manufacturer not found');
        }

        // Get prefix from manufacturer (carpetPrefix or first letter of name)
        $prefix = $manufacturer->getCarpetPrefix() ?? strtoupper(substr($manufacturer->getName(), 0, 1));
        $prefixLength = strlen($prefix);

        // First get all RN numbers with this prefix
        $qb = $this->manager->createQueryBuilder()
            ->select('c.rnNumber')
            ->from(self::ENTITY_CLASS, 'c')
            ->where('c.rnNumber LIKE :prefixPattern')
            ->setParameter('prefixPattern', $prefix . '%');

        $results = $qb->getQuery()->getScalarResult();

        $maxNumber = 0;
        foreach ($results as $result) {
            $rnNumber = $result['rnNumber'];
            $numericPartWithSuffix = substr($rnNumber, $prefixLength);
            $dashPosition = strpos($numericPartWithSuffix, '-');
            $numericPart = $dashPosition === false
                ? $numericPartWithSuffix
                : substr($numericPartWithSuffix, 0, $dashPosition);

            if (is_numeric($numericPart)) {
                $currentNumber = (int) $numericPart;
                if ($currentNumber > $maxNumber) {
                    $maxNumber = $currentNumber;
                }
            }
        }

        // Format the number with leading zeros (adjust length as needed)
        $nextNumber = $maxNumber + 1;
        $numberLength = 4; // or whatever length you want for the numeric part
        $formattedNumber = str_pad((string)$nextNumber, $numberLength, '0', STR_PAD_LEFT);

        return $prefix . $formattedNumber;
    }
}