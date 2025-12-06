<?php

declare(strict_types=1);

namespace App\Menu\Bus\Query\GetMenus;

use App\Common\Bus\Query\QueryHandler;
use App\Menu\Repository\MenuRepository;

/**
 * This class is responsible for handling the 'get menus' query, retrieving.
 */
final readonly class GetMenusQueryHandler implements QueryHandler
{
    /**
     * Constructor with MenuRepository injection.
     *
     * @param MenuRepository $menuRepository menu repository interface
     */
    public function __construct(
        private MenuRepository $menuRepository
    ) {
    }

    public function __invoke(GetMenusQuery $query): GetMenusResponse
    {
        $menus = $this->menuRepository->findAll();

        $formattedMenus = array_map(fn($menu) => [
            'menu_id' => $menu->getId(),
            'name' => $menu->getName(),
            'position' => $menu->getPosition(),
            'route' => $menu->getRoute(),
            'parent_id' => $menu->getParentId(),
        ], $menus);

        return new GetMenusResponse(
            $formattedMenus
        );
    }
}
