<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroupPrice\CreateCollectionGroupPriceCommand;
use App\Setting\DTO\CreateCollectionGroupPriceRequestDto;
use App\Setting\Entity\CollectionGroupPrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createCollectionGroupPrice', name: 'collection_group_price_creation', methods: ['POST'])]
class CreateCollectionGroupPriceController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Collection Group creation',
        content: new Model(type: CollectionGroupPrice::class)
    )]
    #[OA\RequestBody(
        description: 'Collection Group data',
        content: new OA\JsonContent(
            required: ['group_number', 'price', 'tarif_group_id'],
            properties: [
                new OA\Property(property: 'group_number', type: 'integer'),
                new OA\Property(property: 'price', type: 'float'),
                new OA\Property(property: 'price_max', type: 'float', nullable: true),
                new OA\Property(property: 'tarif_group_id', type: 'integer'),
            ]
        ))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateCollectionGroupPriceRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCollectionGroupPriceCommand(
            $requestDTO->group_number,
            $requestDTO->price,
            $requestDTO->price_max,
            $requestDTO->tarif_group_id
        );

        $collectionGroup = $this->handle($createCommand);

        return SuccessResponse::create(
            'collection_group_price_creation',
            $collectionGroup->toArray()
        );
    }
}
