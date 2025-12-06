<?php

namespace App\CheckingList\Controller\QualityCheck;

use App\CheckingList\Bus\Query\GetQualityCheck\GetQualityCheckQuery;
use App\CheckingList\Entity\QualityCheck;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetQualityCheckController extends CommandQueryController
{
    #[Route('/api/qualityChecks', name: 'quality_check_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: QualityCheck::class)))]))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetQualityCheckQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('quality_check_list', $response->toArray());
    }
}
