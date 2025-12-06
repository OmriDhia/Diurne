<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetStatuses\GetCarpetStatusesQuery;
use App\Contremarque\Entity\CarpetStatus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetStatusesController extends CommandQueryController
{
    #[Route('/api/contremarque/carpetDesignOrder/statuses', name: 'get_carpet_statuses', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get Carpet Statuses',
        content: new Model(type: CarpetStatus::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }
        $query = new GetCarpetStatusesQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_carpet_statuses',
            $response->toArray()
        );
    }
}
