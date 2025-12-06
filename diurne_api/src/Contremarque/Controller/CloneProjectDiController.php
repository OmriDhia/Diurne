<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CloneProjectDi\CloneProjectDiCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CloneProjectDiController extends CommandQueryController
{
    #[Route(
        path: '/api/cloneProjectDi/{projectDiId}',
        name: 'clone_project_di',
        methods: ['POST']
    )]
    #[OA\Post(
        path: '/api/cloneProjectDi/{projectDiId}',
        tags: ['Contremarque'],
        summary: 'Clone a ProjectDi',
        responses: [
            new OA\Response(response: 200, description: 'ProjectDi cloned')
        ]
    )]
    public function __invoke(int $projectDiId): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }
//test
        $command = new CloneProjectDiCommand($projectDiId);
        $response = $this->handle($command);

        return SuccessResponse::create('project_di_cloned', $response->toArray());
    }
}
