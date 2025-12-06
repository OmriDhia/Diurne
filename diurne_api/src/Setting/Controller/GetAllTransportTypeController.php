<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TransportType\GetAllTransportTypeQuery;
use App\Setting\Entity\TransportType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/transportType', name: 'get_all_transportTypes', methods: ['GET'])]
class GetAllTransportTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available transportType',
        content: new Model(type: TransportType::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all TransportType',
        content: new OA\JsonContent(
        ))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllTransportTypeQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_transportTypes',
            $response->toArray()
        );
    }
}
