<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Currency;
use App\Setting\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCurrencyRepository extends DoctrineORMRepository implements CurrencyRepository
{
    protected const ENTITY_CLASS = Currency::class;
    protected const ALIAS = 'currency';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Currency $currency, $flush = false): void
    {
        $this->manager->persist($currency);

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
