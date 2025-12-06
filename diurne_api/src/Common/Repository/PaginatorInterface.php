<?php

namespace App\Common\Repository;

use IteratorAggregate;
use Countable;

/**
 * @template T of object
 *
 * @extends IteratorAggregate<array-key, T>
 */
interface PaginatorInterface extends IteratorAggregate, Countable
{
    public function getCurrentPage(): int;

    public function getItemsPerPage(): int;

    public function getLastPage(): int;

    public function getTotalItems(): int;
}
