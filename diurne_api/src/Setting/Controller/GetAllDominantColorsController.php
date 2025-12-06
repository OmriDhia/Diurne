<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\DominantColor\GetAllDominantColorsQuery;
use App\Setting\Bus\Query\DominantColor\GetAllDominantColorsResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/dominant-colors', name: 'get_all_dominant_colors', methods: ['GET'])]
class GetAllDominantColorsController extends CommandQueryController
{
    private const HTTP_STATUS_UNAUTHORIZED = 401;
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_ITEMS_PER_PAGE = 0;
 
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
    #[OA\Get(
        description: 'Retrieve all DominantColors',
        summary: 'Get all dominant colors', 
        responses: [
            new OA\Response(
                response: 200,
                description: 'Array of dominant colors',
                content: new Model(type: GetAllDominantColorsResponse::class)
            ),
        ]
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

        $query = new GetAllDominantColorsQuery($page, $itemsPerPage);
        /** @var GetAllDominantColorsResponse $response */
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_dominant_colors',
            $response->toArray(),

        );
    }
}
