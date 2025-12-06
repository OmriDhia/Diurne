<?php

namespace App\Workshop\Controller\WorkshopOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateWorkshopOrder\UpdateWorkshopOrderCommand;
use App\Workshop\DTO\WorkshopOrder\UpdateWorkshopOrderRequestDto;
use App\Workshop\Entity\WorkshopOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateWorkshopOrderController extends CommandQueryController
{
    #[Route('/api/workshopOrders/{id}', name: 'workshop_order_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop order updated successfully',
        content: new Model(type: WorkshopOrder::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop order update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'reference', type: 'string', example: 'ORD-12345-updated'),
                new OA\Property(property: 'image_command_id', type: 'integer', example: 2),
                new OA\Property(property: 'workshop_information_id', type: 'integer', example: 2)
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop Order ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                $id,
        #[MapRequestPayload] UpdateWorkshopOrderRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateWorkshopOrderCommand(
            id: $id,
            reference: $requestDto->reference,
            imageCommandId: $requestDto->image_command_id,
            workshopInformationId: $requestDto->workshop_information_id
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'workshop_order_updated',
            $response->toArray(),
            'Workshop order updated successfully'
        );
    }
}