<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetAllSpecialShape\GetAllSpecialShapeQuery;
use App\Setting\Entity\SpecialShape;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/specialShapes', name: 'specialShapes_retrieval', methods: ['GET'])]
class GetAllSpecialShapeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available specialShapes',
        content: new Model(type: SpecialShape::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all specialShapes',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllSpecialShapeQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'specialShapes_retrieval',
            $response->toArray(),
            'SpecialShapes fetched successfully'
        );
    }
}
