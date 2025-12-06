<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetAllModels\GetAllModelsQuery;
use App\Setting\Entity\Model as ModelEntity;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/models', name: 'model_get_all', methods: ['GET'])]
class GetAllModelsController extends CommandQueryController
{
    #[OA\Parameter(
        name: "page",
        in: "query",
        description: "Page number",
        required: false,
        schema: new OA\Schema(type: "integer", default: 1)
    )]
    #[OA\Parameter(
        name: "itemsPerPage",
        in: "query",
        description: "Number of items per page",
        required: false,
        schema: new OA\Schema(type: "integer", default: 10)
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetch paginated models',
        content: new Model(type: ModelEntity::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $page = (int) $request->query->get('page', null);
        $itemsPerPage = (int) $request->query->get('itemsPerPage', null);

        $query = new GetAllModelsQuery($page, $itemsPerPage);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'model_get_all',
            $response->toArray(),
            'Models fetched successfully'
        );
    }
}