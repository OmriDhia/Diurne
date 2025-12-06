<?php

namespace App\Contremarque\Controller\RnAttribution;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateRnAttribution\CreateRnAttributionCommand;
use App\Contremarque\DTO\RnAttribution\CreateRnAttributionRequestDto;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateRnAttributionController extends CommandQueryController
{
    #[Route('/api/rnAttributions', name: 'rn_attribution_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Rn Attribution created',
        content: new Model(type: RnAttribution::class)
    )]
    #[OA\RequestBody(
        description: 'Rn Attribution data',
        content: new OA\JsonContent(ref: new Model(type: CreateRnAttributionRequestDto::class))
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(
        Request                                            $request,
        #[MapRequestPayload] CreateRnAttributionRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized to create Rn Attribution'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        try {
            $command = new CreateRnAttributionCommand(
                carpetOrderDetailId: $requestDto->carpetOrderDetailId,
                carpetId: $requestDto->carpetId,
                attributedAt: $requestDto->attributedAt,
                canceledAt: $requestDto->canceledAt
            );

            $rnAttribution = $this->handle($command);

            return SuccessResponse::create(
                'rn_attribution_created',
                $rnAttribution->toArray(),
                'Rn Attribution created successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}