<?php

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetProjectDiById\GetProjectDiByIdQuery;
use App\Contremarque\Bus\Query\GetProjectDiById\ProjectDiResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/project-di/{id}', name: 'get_project_di_by_id', methods: ['GET'])]
#[OA\Get(
    path: '/api/project-di/{id}',
    summary: 'Get a ProjectDi by its ID',
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            description: 'The ID of the ProjectDi',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns the details of a ProjectDi',
            content: new OA\JsonContent(type: 'object')
        ),
        new OA\Response(
            response: 404,
            description: 'ProjectDi not found'
        ),
    ]
)]
#[OA\Tag(name: 'Contremarque')]
class GetProjectDiByIdController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        try {
            /** @var ProjectDiResponse $response */
            $response = $this->ask(new GetProjectDiByIdQuery($id));
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }

        return SuccessResponse::create(
            'get_project_di_by_id',
            $response->getData()
        );
    }
}
