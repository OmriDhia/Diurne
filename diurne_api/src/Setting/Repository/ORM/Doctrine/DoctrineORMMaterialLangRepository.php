<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Material;
use App\Setting\Entity\MaterialLang;
use App\Setting\Repository\MaterialLangRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMMaterialLangRepository extends DoctrineORMRepository implements MaterialLangRepository
{
    protected const ENTITY_CLASS = MaterialLang::class;
    protected const ALIAS = 'materialLang';

    /**
     * DoctrineORMMaterialLangRepository constructor.
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

    public function findMaterialByLabel(string $label): ?Material
    {
        return $this->manager->createQueryBuilder()
            ->select('m')
            ->from(Material::class, 'm')
            ->join('m.materialLangs', 'ml')
            ->join('ml.language', 'l')
            ->where('ml.label = :label')
            ->andWhere('l.iso_code = :iso_code')  // Assuming the ID for French is 1
            ->setParameter('label', $label)
            ->setParameter('iso_code', 'en')   // Remplacer par l'ID de la langue française
            ->getQuery()
            ->getOneOrNullResult();
    }
}
