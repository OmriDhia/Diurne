<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Conversion\GetAllConversionQuery;
use App\Setting\Entity\Conversion;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/conversions', name: 'get_all_conversions', methods: ['GET'])]
class GetAllConversionsController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available conversions',
        content: new Model(type: Conversion::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all Conversion data',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllConversionQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_conversions',
            $response->toArray(),
            'Conversions fetched successfully'

        );
    }
}
