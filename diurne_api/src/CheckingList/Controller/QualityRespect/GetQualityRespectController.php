<?php

namespace App\CheckingList\Controller\QualityRespect;

use App\CheckingList\Bus\Query\GetQualityRespect\GetQualityRespectQuery;
use App\CheckingList\Entity\QualityRespect;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetQualityRespectController extends CommandQueryController
{
    #[Route('/api/qualityRespects', name: 'quality_respect_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: QualityRespect::class)))]))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetQualityRespectQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('quality_respect_list', $response->toArray());
    }
}
