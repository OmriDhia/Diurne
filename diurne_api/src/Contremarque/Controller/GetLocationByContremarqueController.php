<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\Location\GetLocationByContremarqueQuery;
use App\Contremarque\Bus\Query\Location\GetLocationByContremarqueResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetLocationByContremarqueController extends CommandQueryController
{
    #[Route('/api/locationsByContremarque/{contremarqueId}', name: 'locations_by_contremarque', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get locations by Contremarque',
        content: new OA\JsonContent(
            ref: LocationResponse::class
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $contremarqueId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetLocationByContremarqueQuery($contremarqueId);
        /** @var GetLocationByContremarqueResponse $locationResponse */
        $locationResponse = $this->ask($query);

        return SuccessResponse::create(
            'locations_by_contremarque',
            $locationResponse->toArray()
        );
    }
}
