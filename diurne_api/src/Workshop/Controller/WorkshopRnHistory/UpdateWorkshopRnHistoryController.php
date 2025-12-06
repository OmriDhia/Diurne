<?php

namespace App\Workshop\Controller\WorkshopRnHistory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateWorkshopRnHistory\UpdateWorkshopRnHistoryCommand;
use App\Workshop\DTO\WorkshopRnHistory\UpdateWorkshopRnHistoryRequestDto;
use App\Workshop\Entity\WorkshopRnHistory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateWorkshopRnHistoryController extends CommandQueryController
{
    #[Route('/api/workshopRnHistories/{id}', name: 'workshop_rn_history_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop RN history updated',
        content: new Model(type: WorkshopRnHistory::class)
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop RN History ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        description: 'Workshop RN history update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "eventTypeId", type: "integer", nullable: true),
                new OA\Property(property: "locationId", type: "integer", nullable: true),
                new OA\Property(property: "customerId", type: "integer", nullable: true),
                new OA\Property(property: "workshopOrderId", type: "integer", nullable: true),
                new OA\Property(property: "beginAt", type: "string", format: "date-time", nullable: true),
                new OA\Property(property: "endAt", type: "string", format: "date-time", nullable: true),
                new OA\Property(property: "updatedAt", type: "string", format: "date-time", nullable: true)
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                    $id,
        #[MapRequestPayload] UpdateWorkshopRnHistoryRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        $command = new UpdateWorkshopRnHistoryCommand(
            id: $id,
            eventTypeId: $requestDTO->eventTypeId,
            locationId: $requestDTO->locationId,
            customerId: $requestDTO->customerId,
            workshopOrderId: $requestDTO->workshopOrderId,
            carpetId: $requestDTO->carpetId,
            beginAt: $requestDTO->beginAt,
            endAt: $requestDTO->endAt,
            updatedAt: $requestDTO->updatedAt
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_rn_history_update',
                $response->toArray(),
                'Workshop RN history updated successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}