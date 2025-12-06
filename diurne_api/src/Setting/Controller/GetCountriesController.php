<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetCountries\GetCountriesQuery;
use App\Setting\Entity\Country;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCountriesController extends CommandQueryController
{
    #[Route('/api/countries', name: 'get_countries', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get countries',
        content: new Model(type: Country::class)
    )]
    #[OA\RequestBody(
        description: 'Country data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemPerPage', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getCountries = new GetCountriesQuery();
        $response = $this->ask($getCountries);

        return SuccessResponse::create(
            'get_countries',
            $response
        );
    }
}
