<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\CarpetComposition\GetByIdCarpetCompositionQuery;
use App\Contremarque\Entity\CarpetComposition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdCarpetCompositionController extends CommandQueryController
{
    #[Route('/api/CarpetComposition/{id}', name: 'get_by_id_carpetComposition', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'CarpetComposition retrieval',
        content: new Model(type: CarpetComposition::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdCarpetCompositionQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            'get_by_id_carpetComposition',
            $response->toArray()
        );
    }
}
