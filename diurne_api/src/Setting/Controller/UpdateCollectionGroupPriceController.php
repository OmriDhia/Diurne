<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroupPrice\UpdateCollectionGroupPriceCommand;
use App\Setting\DTO\UpdateCollectionGroupPriceRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCollectionGroupPriceController extends CommandQueryController
{
    #[Route('/api/collection-group-price/{id}', name: 'collectionGroupPrice_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'CollectionGroupPrice updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateCollectionGroupPriceRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['collection_group_id', 'price', 'price_max', 'tarif_group_id'],
            properties: [
                new OA\Property(property: 'collection_group_id', type: 'integer'),
                new OA\Property(property: 'price', type: 'string'),
                new OA\Property(property: 'price_max', type: 'string'),
                new OA\Property(property: 'tarif_group_id', type: 'integer'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateCollectionGroupPriceRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        
        // Handle the update event command
        $updateCommand = new UpdateCollectionGroupPriceCommand(
            $id,
            $updateDto->collection_group_id,
            $updateDto->price,
            $updateDto->price_max,
            $updateDto->tarif_group_id
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'collectionGroupPrice_update',
            $response->toArray(),
            'CollectionGroupPrice updated successfully'
        );
    }
}
