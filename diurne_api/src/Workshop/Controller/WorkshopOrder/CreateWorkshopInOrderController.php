<?php

namespace App\Workshop\Controller\WorkshopOrder;

use App\Common\Controller\CommandQueryController;


use App\Common\Response\SuccessResponse;

use App\Workshop\Bus\Command\CreateWorkshopOrder\CreateWorkshopOrderCommand;
use App\Workshop\Bus\Command\CreateWorkshopOrder\UpdateWorkshopOrderCommand;
use App\Workshop\DTO\WorkshopOrder\CreateWorkshopOrderRequestDto;
use App\Workshop\Entity\WorkshopOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;


class CreateWorkshopInOrderController extends CommandQueryController
{
    #[Route('/api/workshopOrders', name: 'workshop_order_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Workshop order created successfully',
        content: new Model(type: WorkshopOrder::class)
    )]
    #[OA\RequestBody(
        description: 'Workshop order data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'reference', type: 'string', example: 'ORD-12345'),
                new OA\Property(property: 'image_command_id', type: 'integer', example: 1),
                new OA\Property(property: 'workshop_information_id', type: 'integer', example: 1)
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateWorkshopOrderRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);

        }

        $command = new CreateWorkshopOrderCommand(
            reference: $requestDto->reference,
            imageCommandId: $requestDto->image_command_id,
            workshopInformationId: $requestDto->workshop_information_id
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'workshop_order_created',
            $response->toArray(),
            'Workshop order created successfully',
            Response::HTTP_CREATED
        );
    }
}