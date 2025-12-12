<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Workshop;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\Workshop\GetWorkshop\GetWorkshopQuery;
use App\MobileAppApi\Entity\Workshop;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopController extends CommandQueryController
{
    #[Route('/api/mobile/workshops/{id}', name: 'get_workshop', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get Workshop',
        content: new Model(type: Workshop::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetWorkshopQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_workshop',
            $response->toArray(),
            'Workshop retrieved successfully'
        );
    }
}
