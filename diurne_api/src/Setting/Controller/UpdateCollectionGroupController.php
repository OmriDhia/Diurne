<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroup\UpdateCollectionGroupCommand;
use App\Setting\DTO\UpdateCollectionGroupRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCollectionGroupController extends CommandQueryController
{
    #[Route('/api/collection-group/{id}', name: 'collectionGroup_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'CollectionGroup updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateCollectionGroupRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'label', type: 'string'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateCollectionGroupRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateCollectionGroupCommand(
            $id,
            $updateDto->collection_group_id,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'collectionGroup_update',
            $response->toArray(),
            "CollectionGroup updated successfully"
        );
    }
}
