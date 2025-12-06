<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\DesignerComposition;
use App\Contremarque\Repository\DesignerCompositionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMDesignerCompositionRepository extends DoctrineORMRepository implements DesignerCompositionRepository
{
    protected const ENTITY_CLASS = DesignerComposition::class;
    protected const ALIAS = 'designerComposition';

    /**
     * DoctrineORMDesignerCompositionRepository constructor.
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
