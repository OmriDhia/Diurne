<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;


use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Repository\CarpetOrderDetailRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class DoctrineORMCarpetOrderDetailRepository extends DoctrineORMRepository implements CarpetOrderDetailRepository
{
    protected const ENTITY_CLASS = CarpetOrderDetail::class;
    protected const ALIAS = 'carpetOrderDetail';


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

    public function getNextCarpetOrderDetailNumberInQuote($quoteReference)
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
        } catch (NoResultException|NonUniqueResultException) {
            $lastReference = null; // Aucun enregistrement trouvé
        }

        // Extraire et incrémenter le suffixe
        if ($lastReference) {
            preg_match('/_T(\d{2})$/', $lastReference, $matches);
            $currentMaxSuffix = isset($matches[1]) ? (int)$matches[1] : 0;
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
            $nextSuffixFormatted = str_pad((string)$nextSuffix, 2, '0', STR_PAD_LEFT);
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
}
