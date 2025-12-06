<?php

namespace App\CheckingList\Controller\QualityCheck;

use App\CheckingList\Bus\Query\GetQualityCheckById\GetQualityCheckByIdQuery;
use App\CheckingList\Entity\QualityCheck;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetQualityCheckByIdController extends CommandQueryController
{
    #[Route('/api/qualityChecks/{id}', name: 'quality_check_get_by_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Single', content: new Model(type: QualityCheck::class))]
    #[OA\Parameter(name: 'id', description: 'Quality check id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetQualityCheckByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('quality_check_item', $response->toArray());
    }
}
