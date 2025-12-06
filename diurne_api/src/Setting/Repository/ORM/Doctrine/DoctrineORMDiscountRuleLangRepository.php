<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\DiscountRuleLang;
use App\Setting\Repository\DiscountRuleLangRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMDiscountRuleLangRepository extends DoctrineORMRepository implements DiscountRuleLangRepository
{
    protected const ENTITY_CLASS = DiscountRuleLang::class;
    protected const ALIAS = 'discountRuleLang';

    /**
     * DoctrineORMDiscountRuleLangRepository constructor.
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
}
