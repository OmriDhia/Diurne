<?php

declare(strict_types=1);

namespace App\Contremarque\Service;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Contremarque\Entity\CarpetSpecification;

class CarpetReferenceGenerator
{
    /**
     * Stores the last generated number for a given prefix within a single process
     * to avoid duplicate references when multiple specifications are created
     * before the database is flushed.
     * @var array<string, int>
     */
    private array $lastGeneratedNumbers = [];

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getNextReference(?DateTimeInterface $date = null): string
    {
        $date ??= new DateTimeImmutable();
        $yearMonth = $date->format('y-m');
        $prefix = 'csp';
        $base = $prefix . $yearMonth;

        if (!isset($this->lastGeneratedNumbers[$base])) {
            $pattern = sprintf('%s%%', $base);

            $qb = $this->entityManager->createQueryBuilder()
                ->select('cs.carpetReference')
                ->from(CarpetSpecification::class, 'cs')
                ->where('cs.carpetReference LIKE :pattern')
                ->setParameter('pattern', $pattern)
                ->orderBy('cs.carpetReference', 'DESC')
                ->setMaxResults(1);

            /** @var array<string, string>|null $result */
            $result = $qb->getQuery()->getOneOrNullResult();

            $nextNumber = 1;
            if (!empty($result['carpetReference'])) {
                $lastRef = $result['carpetReference'];
                $lastNumber = (int) substr($lastRef, strlen($base));
                $nextNumber = $lastNumber + 1;
            }

            $this->lastGeneratedNumbers[$base] = $nextNumber;
        } else {
            $this->lastGeneratedNumbers[$base]++;
        }

        return sprintf('%s%03d', $base, $this->lastGeneratedNumbers[$base]);
    }
}
