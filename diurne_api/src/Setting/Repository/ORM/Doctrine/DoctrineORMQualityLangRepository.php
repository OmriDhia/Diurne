<?php

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\QualityLang;
use App\Setting\Repository\QualityLangRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMQualityLangRepository extends DoctrineORMRepository implements QualityLangRepository
{
    protected const ENTITY_CLASS = QualityLang::class;
    protected const ALIAS = 'qualityLang';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(QualityLang $quality): void
    {
        $this->persist($quality);
        $this->flush();
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
    }
}
