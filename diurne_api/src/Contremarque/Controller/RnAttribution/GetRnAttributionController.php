<?php

namespace App\Contremarque\Controller\RnAttribution;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Query\GetRnAttribution\GetRnAttributionQuery;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;

class GetRnAttributionController extends CommandQueryController
{
    #[Route('/api/rnAttributions', name: 'rn_attribution_list', methods: ['GET'])]
    #[OA\Parameter(
        name: 'carpetOrderDetailId',
        description: 'Filter by Carpet Order Detail ID',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns List of Rn Attributions',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: RnAttribution::class))
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Rn Attributions not found'
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetRnAttributionQuery();

        $rnAttributions = $this->ask($query);

        if (empty($rnAttributions->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'No Rn Attributions found'],
                404
            );
        }

        return SuccessResponse::create(
            'rn_attributions_retrieved',
            $rnAttributions->toArray()
        );
    }
}