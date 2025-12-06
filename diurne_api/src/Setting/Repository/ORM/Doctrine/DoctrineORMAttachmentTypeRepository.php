<?php

// src/App/Setting/Repository/ORM/Doctrine/DoctrineORMAttachmentTypeRepository.php

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\AttachmentType;
use App\Setting\Repository\AttachmentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMAttachmentTypeRepository extends DoctrineORMRepository implements AttachmentTypeRepository
{
    protected const ENTITY_CLASS = AttachmentType::class;
    protected const ALIAS = 'attachmentType';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(AttachmentType $attachmentType): void
    {
        $this->persist($attachmentType);
        $this->flush();
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // Implement create() method logic here
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // Implement update() method logic here
    }
}
