<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;
use App\Contremarque\Repository\CarpetDesignOrderAttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetDesignOrderAttachmentRepository extends DoctrineORMRepository implements CarpetDesignOrderAttachmentRepository
{
    protected const ENTITY_CLASS = CarpetDesignOrderAttachment::class;
    protected const ALIAS = 'carpetDesignOrderAttachment';

    /**
     * DoctrineORMCarpetDesignOrderAttachmentRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function deleteByAttachment($image): void
    {
        $this->manager->createQueryBuilder()
            ->delete(self::ENTITY_CLASS, self::ALIAS)
            ->where(self::ALIAS . '.attachment = :attachment')
            ->setParameter('attachment', $image)
            ->getQuery()
            ->execute();
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
}
