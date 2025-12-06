<?php
declare(strict_types=1);

namespace App\Contremarque\Controller\CarpetOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateCarpetOrder\CreateCarpetOrderCommand;
use App\Contremarque\DTO\CarpetOrder\CreateCarpetOrderRequestDto;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCarpetOrderController extends CommandQueryController
{
    #[Route('/api/carpetOrder', name: 'carpet_order_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Carpet order created successfully',
        content: new Model(type: CarpetOrder::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet order data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'originalQuoteId', type: 'integer'),
                new OA\Property(property: 'clonedQuoteId', type: 'integer'),
                new OA\Property(property: 'contremarqueId', type: 'integer'),
                new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', nullable: true)
            ]
        )
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(
        #[MapRequestPayload] CreateCarpetOrderRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $command = new CreateCarpetOrderCommand(
            originalQuoteId: $requestDto->originalQuoteId,
            clonedQuoteId: $requestDto->clonedQuoteId,
            contremarqueId: $requestDto->contremarqueId,
            createdAt: $requestDto->createdAt ? new \DateTime($requestDto->createdAt) : null,
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'carpet_order_created',
            $response->toArray(),
            'Carpet order created successfully'
        );
    }
}