<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\Contremarque;

interface ContremarqueRepository extends BaseRepository
{
    public function findOneByName(string $name): ?Contremarque;

    public function findOneByNumber(string $number): ?Contremarque;

    public function getNextProjectNumber(): string;

    public function findByFilters(array $filters, int $page, int $limit, string $orderBy, string $orderWay, bool $countOnly = false);

    public function findOneById(int $id): ?Contremarque;
    
    public function findOneByIdWithRelations(int $id): ?Contremarque;
}
