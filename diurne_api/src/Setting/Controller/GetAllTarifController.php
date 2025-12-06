<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Tarif\GetAllTarifQuery;
use App\Setting\DTO\GetAllTarifRequestDto;
use App\Setting\Entity\Tarif;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/tarifs', name: 'get_all_tarifs', methods: ['GET'])]
class GetAllTarifController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available tarifs',
        content: new Model(type: Tarif::class)
    )]
    #[OA\Parameter(
        name: 'discountRuleId',
        in: 'query',
        description: 'Filter tarifs by discount rule ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $dto = new GetAllTarifRequestDto(
            $request->query->getInt('discountRuleId', 0) // Map query param to DTO
        );

        $query = new GetAllTarifQuery($dto); // Pass the DTO to the query
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_tarifs',
            $response->toArray(),
            'Tarifs fetched successfully'

        );
    }
}
