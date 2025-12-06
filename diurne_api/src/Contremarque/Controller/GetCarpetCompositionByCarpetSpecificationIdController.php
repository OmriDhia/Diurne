<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\CarpetComposition\GetAllCarpetCompositionQuery;
use App\Contremarque\Entity\CarpetComposition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/CarpetSpecification/{carpetSpecificationId}/carpetCompositions', name: 'get_all_carpetCompositions', methods: ['GET'])]
class GetCarpetCompositionByCarpetSpecificationIdController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available carpetComposition by carpetSpecificationId',
        content: new Model(type: CarpetComposition::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all CarpetComposition by carpetSpecificationId',
        content: new OA\JsonContent(
        ))]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $carpetSpecificationId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllCarpetCompositionQuery($carpetSpecificationId);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_carpetCompositions',
            $response->toArray()
        );
    }
}
