<?php

namespace App\Contremarque\Controller\RnAttribution;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateRnAttribution\UpdateRnAttributionCommand;
use App\Contremarque\DTO\RnAttribution\UpdateRnAttributionRequestDto;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateRnAttributionController extends CommandQueryController
{
    #[Route('/api/rnAttributions/{id}', name: 'rn_attribution_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Rn Attribution updated',
        content: new Model(type: RnAttribution::class)
    )]
    #[OA\RequestBody(
        description: 'Rn Attribution update data',
        content: new OA\JsonContent(ref: new Model(type: UpdateRnAttributionRequestDto::class))
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID of the Rn Attribution to update',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(
        int                                                $id,
        #[MapRequestPayload] UpdateRnAttributionRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized to update Rn Attribution'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        try {
            $command = new UpdateRnAttributionCommand(
                id: $id,
                rn: $requestDto->rn,
                attributedAt: $requestDto->attributedAt,
                canceledAt: $requestDto->canceledAt
            );

            $rnAttribution = $this->handle($command);

            return SuccessResponse::create(
                'rn_attribution_updated',
                $rnAttribution->toArray(),
                'Rn Attribution updated successfully.'
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