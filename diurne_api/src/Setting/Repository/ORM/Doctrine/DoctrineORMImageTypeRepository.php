<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\ImageType;
use App\Setting\Repository\ImageTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMImageTypeRepository extends DoctrineORMRepository implements ImageTypeRepository
{
    protected const ENTITY_CLASS = ImageType::class;
    protected const ALIAS = 'imageType';

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

    /**
     * @return false|null|object
     */
    public function getRandomImageType(): object|false|null
    {
        $sql = 'SELECT imageType.id FROM image_type imageType ORDER BY RAND() ASC LIMIT 1';
        $stmt = $this->manager->getConnection()->prepare($sql);
        $id = $stmt->execute()->fetchOne();
        if (empty($id)) {
            return false;
        }

        return $this->find((int) $id);
    }
}
