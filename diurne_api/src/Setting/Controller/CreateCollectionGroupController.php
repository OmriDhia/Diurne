<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CollectionGroup\CreateCollectionGroupCommand;
use App\Setting\DTO\CreateCollectionGroupRequestDto;
use App\Setting\Entity\CollectionGroup;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createCollectionGroup', name: 'collection_group_creation', methods: ['POST'])]
class CreateCollectionGroupController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Collection Group creation',
        content: new Model(type: CollectionGroup::class)
    )]
    #[OA\RequestBody(
        description: 'Collection Group data',
        content: new OA\JsonContent(
            required: ['group_number'],
            properties: [
                new OA\Property(property: 'group_number', type: 'integer'),
            ]
        ))]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateCollectionGroupRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCollectionGroupCommand(
            $requestDTO->group_number
        );

        $collectionGroup = $this->handle($createCommand);

        return SuccessResponse::create(
            'collection_group_creation',
            $collectionGroup->toArray()
        );
    }
}
