<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetContremarqueById\GetContremarqueByIdQuery;
use App\Contremarque\Entity\Contremarque;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetContremarqueByIdController extends CommandQueryController
{
    #[Route('/api/contremarque/{id}', name: 'get_contremarque_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Contremarque retrieval',
        content: new Model(type: Contremarque::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getContremarqueByIdQuery = new GetContremarqueByIdQuery($id);
        $response = $this->ask($getContremarqueByIdQuery);

        return SuccessResponse::create(
            'get_contremarque_by_id',
            $response
        );
    }
}
