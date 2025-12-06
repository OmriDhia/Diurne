<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TarifGroup\GetAllTarifGroupsQuery;
use App\Setting\Entity\TarifGroup;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GetTarifGroupsController extends CommandQueryController
{
    private const HTTP_STATUS_UNAUTHORIZED = 401;
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_ITEMS_PER_PAGE = 0;

    #[Route('/api/tarifGroups', name: 'get_all_tarif_groups', methods: ['GET'])]
    #[OA\Parameter(
        name: "page",
        in: "query",
        description: "Page number",
        required: false,
        schema: new OA\Schema(type: "integer", default: self::DEFAULT_PAGE)
    )]
    #[OA\Parameter(
        name: "itemsPerPage",
        in: "query",
        description: "Number of items per page",
        required: false,
        schema: new OA\Schema(type: "integer", default: self::DEFAULT_ITEMS_PER_PAGE)
    )]
    #[OA\Response(
        response: 200,
        description: 'Get all Tarif Groups',
        content: new Model(type: TarifGroup::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(
                ['code' => self::HTTP_STATUS_UNAUTHORIZED, 'message' => 'Unauthorized to access this content'],
                self::HTTP_STATUS_UNAUTHORIZED
            );
        }

        $page = (int) $request->query->get('page', self::DEFAULT_PAGE);
        $itemsPerPage = (int) $request->query->get('itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);

        $getAllTarifGroupsQuery = new GetAllTarifGroupsQuery($page, $itemsPerPage);
        $response = $this->ask($getAllTarifGroupsQuery);

        return SuccessResponse::create(
            'get_all_tarif_groups',
            $response->toArray()
        );
    }
}
