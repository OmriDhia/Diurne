<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Workshop;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\Workshop\GetWorkshopList\GetWorkshopListQuery;
use App\MobileAppApi\Entity\Workshop;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopListController extends CommandQueryController
{
    #[Route('/api/mobile/workshops', name: 'get_workshop_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get list of Workshops',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Workshop::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetWorkshopListQuery();
        $response = $this->ask($query);

        $data = array_map(fn(Workshop $w) => $w->toArray(), $response);

        return SuccessResponse::create(
            'get_workshop_list',
            $data,
            'Workshop list retrieved successfully'
        );
    }
}
