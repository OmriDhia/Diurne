<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\SpecialTreatment\GetByIdSpecialTreatmentQuery;
use App\Setting\Entity\SpecialTreatment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdSpecialTreatmentController extends CommandQueryController
{
    #[Route('/api/specialTreatment/{id}', name: 'get_by_id_specialTreatment', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'SpecialTreatment retrieval',
        content: new Model(type: SpecialTreatment::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdSpecialTreatmentQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            'get_by_id_specialTreatment',
            $response->toArray(),
            'SpecialTreatment fetched successfully'
        );
    }
}
