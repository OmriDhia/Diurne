<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\RN;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\RN\GetRNList\GetRNListQuery;
use App\MobileAppApi\Entity\RN;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetRNListController extends CommandQueryController
{
    #[Route('/api/mobile/rn', name: 'get_rn_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get list of RNs',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: RN::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetRNListQuery();
        $response = $this->ask($query);

        $data = array_map(fn(RN $rn) => $rn->toArray(), $response);

        return SuccessResponse::create(
            'get_rn_list',
            $data,
            'RN list retrieved successfully'
        );
    }
}
