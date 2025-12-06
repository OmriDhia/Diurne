<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Quality\GetAllQualitiesQuery;
use App\Setting\Entity\Quality;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/qualities', name: 'quality_get_all', methods: ['GET'])]
class GetAllQualitiesController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available qualities',
        content: new Model(type: Quality::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all qualities',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllQualitiesQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'qualities_retrieval',
            $response->toArray(),
            "Qualities fetched successfully"
        );
    }
}
