<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Manufacturer\GetByIdManufacturerQuery;
use App\Setting\Entity\Manufacturer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdManufacturerController extends CommandQueryController
{
    #[Route('/api/manufacturer/{id}', name: 'get_by_id_manufacturer', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Manufacturer retrieval',
        content: new Model(type: Manufacturer::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getByIdQuery = new GetByIdManufacturerQuery($id);
        $response = $this->ask($getByIdQuery);

        return SuccessResponse::create(
            $response->toArray(),
            'get_by_id_manufacturer'
        );
    }
}
