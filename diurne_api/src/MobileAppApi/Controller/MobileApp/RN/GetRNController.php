<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\RN;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\RN\GetRN\GetRNQuery;
use App\MobileAppApi\Entity\RN;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetRNController extends CommandQueryController
{
    #[Route('/api/mobile/rn/{id}', name: 'get_rn', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get RN',
        content: new Model(type: RN::class)
    )]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetRNQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_rn',
            $response->toArray(),
            'RN retrieved successfully'
        );
    }
}
