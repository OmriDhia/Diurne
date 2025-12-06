<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Police\GetAllPoliceQuery;
use App\Setting\Entity\Quality;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/polices', name: 'police_get_all', methods: ['GET'])]
class GetAllPoliceController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available police entities',
        content: new Model(type: Quality::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all police entities',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllPoliceQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'police_entities_retrieval',
            $response->toArray(),

        );
    }
}
