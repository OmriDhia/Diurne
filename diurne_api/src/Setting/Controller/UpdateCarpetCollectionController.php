<?php

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CarpetCollection\UpdateCarpetCollectionCommand;
use App\Setting\DTO\UpdateCarpetCollectionDto;
use App\Setting\Entity\CarpetCollection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/carpet-collections/{id}', name: 'carpet_collection_update', methods: ['PUT'])]
class UpdateCarpetCollectionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Carpet Collection updated',
        content: new Model(type: CarpetCollection::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet Collection data',
        content: new OA\JsonContent(
            required: ['reference', 'code', 'collection_group_id', 'show_grid'],
            properties: [
                new OA\Property(property: 'reference', type: 'string'),
                new OA\Property(property: 'code', type: 'string'),
                new OA\Property(property: 'collection_group_id', type: 'integer'),
                new OA\Property(property: 'show_grid', type: 'boolean'),
                new OA\Property(property: 'special_shape_id', type: 'integer', nullable: true),
                new OA\Property(property: 'police_id', type: 'integer', nullable: true),
                new OA\Property(property: 'image_name', type: 'string', nullable: true),
                new OA\Property(property: 'author_id', type: 'integer', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateCarpetCollectionDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateCarpetCollectionCommand(
            $id,
            $dto->reference,
            $dto->code,
            $dto->collection_group_id,
            $dto->show_grid,
            $dto->special_shape_id,
            $dto->police_id,
            $dto->image_name,
            $dto->author_id,
        );

        try {
            /** @var CarpetCollection $collection */
            $collection = $this->handle($command);

            return SuccessResponse::create(
                'carpet_collection_update',
                $collection->toArray(),
                'CarpetCollection updated successfully'
            );
        } catch (Exception $exception) {
            return new JsonResponse(['status' => 'error', 'message' => $exception->getMessage()], 500);
        }
    }
}
