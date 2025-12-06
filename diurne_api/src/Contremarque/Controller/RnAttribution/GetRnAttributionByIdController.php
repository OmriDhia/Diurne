<?php

namespace App\Contremarque\Controller\RnAttribution;

use App\Common\Controller\CommandQueryController;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetRnAttributionById\GetRnAttributionByIdQuery;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetRnAttributionByIdController extends CommandQueryController
{
    #[Route('/api/rnAttributions/{id}', name: 'rn_attribution_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns Rn Attribution details',
        content: new Model(type: RnAttribution::class)
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID of the Rn Attribution',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized to view Rn Attribution'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        try {
            $query = new GetRnAttributionByIdQuery($id);
            $rnAttribution = $this->ask($query);

            return SuccessResponse::create(
                'rn_attribution_retrieved',
                $rnAttribution->toArray()
            );
        } catch (ResourceNotFoundException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}