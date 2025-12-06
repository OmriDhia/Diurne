<?php

namespace App\Contremarque\Controller\RnAttribution;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteRnAttribution\DeleteRnAttributionCommand;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteRnAttributionController extends CommandQueryController
{
    #[Route('/api/rnAttributions/{id}', name: 'rn_attribution_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Rn Attribution deleted',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 200),
                new OA\Property(property: 'message', type: 'string', example: 'Rn Attribution deleted successfully')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID of the Rn Attribution to delete',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized to delete Rn Attribution'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        try {
            $command = new DeleteRnAttributionCommand($id);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'rn_attribution_deleted',
                $response->toArray(),
                'Rn Attribution deleted successfully.'
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