<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetPriceBase;
use App\Contremarque\Entity\Location;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Setting\Entity\PriceType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMQuoteDetailRepository extends DoctrineORMRepository implements QuoteDetailRepository
{
    protected const ENTITY_CLASS = QuoteDetail::class;
    protected const ALIAS = 'quoteDetail';

    /**
     * DoctrineORMQuoteDetailRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }
    public function save(QuoteDetail $quoteDetail, bool $flush = true): void
    {
        $this->getEntityManager()->persist($quoteDetail);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

    public function getQuoteDetailPrice(QuoteDetail $quoteDetail, PriceType $priceType): ?CarpetPriceBase
    {
        $query = $this->manager->createQueryBuilder()
            ->select('cpb')
            ->from(CarpetPriceBase::class, 'cpb')
            ->where('cpb.quoteDetail = :quoteDetail')
            ->andWhere('cpb.priceType = :priceType')
            ->setParameter('quoteDetail', $quoteDetail)
            ->setParameter('priceType', $priceType)
            ->getQuery();

        // Use getOneOrNullResult to fetch a single result or null if not found
        return $query->getOneOrNullResult();
    }

    /**
     * @return string
     */
    public function getNextCarpetNumberInQuote($quoteReference)
    {
        // Trouver le plus grand suffixe numérique après '_T' pour le quoteReference donné
        $query = $this->manager->createQueryBuilder()
            ->select('qd.reference')
            ->from(self::ENTITY_CLASS, 'qd')
            ->where('qd.reference LIKE :quoteReference')
            ->setParameter('quoteReference', $quoteReference . '_T%')
            ->orderBy('qd.reference', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        try {
            $lastReference = $query->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException) {
            $lastReference = null; // Aucun enregistrement trouvé
        }

        // Extraire et incrémenter le suffixe
        if ($lastReference) {
            // Matches references like CM062501_T01 or CM062501_T01-1
            preg_match('/_T(\d{2})(?:-\d+)?$/', $lastReference, $matches);
            $currentMaxSuffix = isset($matches[1]) ? (int) $matches[1] : 0;
        } else {
            $currentMaxSuffix = 0;
        }

        // Limiter le suffixe à 2 chiffres
        if ($currentMaxSuffix >= 99) {
            throw new DomainException('Maximum number of carpet references reached for this quote.');
        }

        // Initialisation du suffixe
        $nextSuffix = $currentMaxSuffix + 1;

        // Générer la nouvelle référence et vérifier si elle existe déjà
        do {
            // Générer le suffixe à 2 chiffres
            $nextSuffixFormatted = str_pad((string) $nextSuffix, 2, '0', STR_PAD_LEFT);
            $newReference = $quoteReference . '_T' . $nextSuffixFormatted;

            // Vérifier si la référence existe déjà
            $existingReference = $this->manager->createQueryBuilder()
                ->select('qd.id')
                ->from(self::ENTITY_CLASS, 'qd')
                ->where('qd.reference = :newReference')
                ->setParameter('newReference', $newReference)
                ->getQuery()
                ->getOneOrNullResult();

            // Si la référence existe déjà, incrémenter le suffixe et tester à nouveau
            ++$nextSuffix;
        } while ($existingReference);

        return $newReference;
    }

    public function getAllQuoteDetails(Quote $quote)
    {
        // Create the query to fetch all QuoteDetail entities for the provided Quote
        $query = $this->manager->createQueryBuilder()
            ->select('qd')
            ->from(self::ENTITY_CLASS, 'qd')
            ->where('qd.quote = :quote') // Filter by the provided Quote
            ->setParameter('quote', $quote)
            ->getQuery();

        // Return the result as an array of QuoteDetail entities
        return $query->getResult();
    }

    public function findOneById(int $id): ?QuoteDetail
    {
        return $this->find($id);
    }
    /**
     * @return null|object
     */
    public function findByCarpetDesignOrder($carpetDesignOrder)
    {
        return $this->findOneBy(['carpetDesignOrder' => $carpetDesignOrder]);
    }
    public function hasAssociatedQuoteDetails(Location $location): bool
    {
        $count = $this->manager->createQueryBuilder('quoteDetail')
            ->from(self::ENTITY_CLASS, 'quoteDetail')
            ->select('COUNT(quoteDetail.id)')
            ->andWhere('quoteDetail.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
