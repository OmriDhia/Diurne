<?php

declare(strict_types=1);

namespace App\Menu\Bus\Query\GetMenus;

use App\Common\Bus\Query\QueryResponse;

final class GetMenusResponse implements QueryResponse
{
    /**
     * GetProfilesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $menus
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{menus: array}
     */
    public function toArray(): array
    {
        return [
            'menus' => $this->menus,
        ];
    }
}
