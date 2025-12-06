<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\SpecialTreatment\GetAllSpecialTreatmentQuery;
use App\Setting\Entity\SpecialTreatment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/specialTreatment', name: 'get_all_specialTreatments', methods: ['GET'])]
class GetAllSpecialTreatmentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available specialTreatment',
        content: new Model(type: SpecialTreatment::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all SpecialTreatment',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllSpecialTreatmentQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_specialTreatments',
            $response->toArray(),
            'SpecialTreatments fetched successfully'

        );
    }
}
