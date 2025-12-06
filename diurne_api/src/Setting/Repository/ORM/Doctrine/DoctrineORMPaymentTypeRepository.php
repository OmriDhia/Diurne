<?php

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\PaymentType;
use App\Setting\Repository\PaymentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMPaymentTypeRepository extends DoctrineORMRepository implements PaymentTypeRepository
{
    protected const ENTITY_CLASS = PaymentType::class;
    protected const ALIAS = 'paymentType';

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