<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use DomainException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetReference;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Repository\CarpetReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DoctrineORMCarpetReferenceRepository extends DoctrineORMRepository implements CarpetReferenceRepository
{
    protected const ENTITY_CLASS = CarpetReference::class;
    protected const ALIAS = 'carpetReference';

    /**
     * DoctrineORMAttachmentRepository constructor.
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

    public function getLastSequenceNumber(Contremarque $contremarque): int
    {
        $qb = $this->manager->createQueryBuilder();

        $qb->select('MAX(carpetReference.sequenceNumber)')
            ->from(CarpetReference::class, 'carpetReference')
            ->where('carpetReference.contremarque = :contremarque')
            ->setParameter('contremarque', $contremarque);

        try {
            $lastSequenceNumber = $qb->getQuery()->getSingleScalarResult();

            // Si aucun résultat n'est trouvé, retourne 1
            return (int) ($lastSequenceNumber ? (int) $lastSequenceNumber + 1 : 1);
        } catch (NoResultException|NonUniqueResultException $e) {
            throw new DomainException('Impossible de déterminer le dernier numéro de séquence.', 0, $e);
        }
    }

    public function getLastReference(Contremarque $contremarque): string
    {
        $nextSequenceNumber = $this->getLastSequenceNumber($contremarque);

        return $contremarque->getProjectNumber().' T '.str_pad((string) $nextSequenceNumber, 3, '0', STR_PAD_LEFT);
    }
}
