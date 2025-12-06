<?php

namespace App\Workshop\Controller\WorkshopRnHistory;

use App\Common\Controller\CommandQueryController;

use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateWorkshopRnHistory\CreateWorkshopRnHistoryCommand;

use App\Workshop\DTO\WorkshopRnHistory\CreateWorkshopRnHistoryRequestDto;
use App\Workshop\Entity\WorkshopRnHistory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateWorkshopRnHistoryController extends CommandQueryController
{

    #[Route('/api/workshopRnHistories', name: 'workshop_rn_history_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop RN history created',
        content: new Model(type: WorkshopRnHistory::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop RN history data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "eventTypeId", type: "integer"),
                new OA\Property(property: "locationId", type: "integer"),
                new OA\Property(property: "customerId", type: "integer"),
                new OA\Property(property: "workshopOrderId", type: "integer"),
                new OA\Property(property: "beginAt", type: "string", format: "date-time"),
                new OA\Property(property: "endAt", type: "string", format: "date-time"),
                new OA\Property(property: "createdAt", type: "string", format: "date-time", nullable: true),
                new OA\Property(property: "updatedAt", type: "string", format: "date-time", nullable: true)
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateWorkshopRnHistoryRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        $command = new CreateWorkshopRnHistoryCommand(
            eventTypeId: $requestDTO->eventTypeId,
            locationId: $requestDTO->locationId,
            customerId: $requestDTO->customerId,
            workshopOrderId: $requestDTO->workshopOrderId,
            beginAt: $requestDTO->beginAt,
            endAt: $requestDTO->endAt,
            carpetId: $requestDTO->carpetId,
            createdAt: $requestDTO->createdAt,
            updatedAt: $requestDTO->updatedAt
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'workshop_rn_history_creation',
                $response->toArray(),
                'Workshop RN history created successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}