<?php

namespace App\Common\Repository;

use IteratorAggregate;
use Countable;
use Iterator;

interface BaseRepository extends IteratorAggregate, Countable
{
    public function find(int $id);

    public function findAll();

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null);

    public function findOneBy(array $criteria);

    public function create(array $data);

    public function update($entity, array $data);

    public function remove(object $entity);

    public function persist(object $entity);

    public function flush(): void;

    public function getIterator(): Iterator;

    public function count(): int;
    public function getEntityManager();
}
