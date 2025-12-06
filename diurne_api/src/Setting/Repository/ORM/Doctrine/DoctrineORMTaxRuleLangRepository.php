<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\TaxRuleLang;
use App\Setting\Repository\TaxRuleLangRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMTaxRuleLangRepository extends DoctrineORMRepository implements TaxRuleLangRepository
{
    protected const ENTITY_CLASS = TaxRuleLang::class;
    protected const ALIAS = 'taxRuleLang';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(TaxRuleLang $taxRuleLang, $flush = false): void
    {
        $this->manager->persist($taxRuleLang);

        if ($flush) {
            $this->manager->flush();
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
}
