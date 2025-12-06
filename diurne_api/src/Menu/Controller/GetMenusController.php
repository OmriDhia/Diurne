<?php

declare(strict_types=1);

namespace App\Menu\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Menu\Bus\Query\GetMenus\GetMenusQuery;
use App\Menu\Entity\Menu;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetMenusController extends CommandQueryController
{
    #[Route('/api/menus', name: 'get_menus', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get menus',
        content: new Model(type: Menu::class)
    )]
    #[OA\RequestBody(
        description: 'Menu data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'int'),
                new OA\Property(property: 'position', type: 'int'),
                new OA\Property(property: 'parent_id', type: 'int'),
                new OA\Property(property: 'route', type: 'string'),
            ]
        ))]
    #[OA\Tag(name: 'Menu')]
    public function __invoke(
    ): JsonResponse {
        $getMenus = new GetMenusQuery();
        $response = $this->ask($getMenus);

        return SuccessResponse::create(
            'get_menus',
            $response
        );
    }
}
