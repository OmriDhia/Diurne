<?php

// src/Setting/Controller/CreateCarpetCollectionController.php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\CarpetCollection\CreateCarpetCollectionCommand;
use App\Setting\Bus\Command\CarpetCollection\CreateCarpetCollectionLangCommand;
use App\Setting\DTO\CreateCarpetCollectionDto;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\CarpetCollectionLang;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/carpet-collections', name: 'carpet_collection_create', methods: ['POST'])]
class CreateCarpetCollectionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Carpet Collection creation',
        content: new Model(type: CarpetCollection::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet Collection data',
        content: new OA\JsonContent(
            required: ['reference', 'code', 'collection_group_id'],
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
    public function __invoke(
        #[MapRequestPayload] CreateCarpetCollectionDto $createCarpetCollectionDto
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Create CarpetCollection entity
        $createCommand = new CreateCarpetCollectionCommand(
            $createCarpetCollectionDto->reference,
            $createCarpetCollectionDto->code,
            $createCarpetCollectionDto->collection_group_id,
            $createCarpetCollectionDto->show_grid,
            $createCarpetCollectionDto->special_shape_id,
            $createCarpetCollectionDto->police_id,
            $createCarpetCollectionDto->image_name,
            $createCarpetCollectionDto->author_id
        );

        /** @var CarpetCollection $carpetCollection */
        $carpetCollection = $this->handle($createCommand);

        // Create CarpetCollectionLang entities
        foreach ($createCarpetCollectionDto->languages as $languageDto) {
            $createLangCommand = new CreateCarpetCollectionLangCommand(
                $carpetCollection->getId(),
                $languageDto->description,
                $languageDto->languageId
            );

            $this->handle($createLangCommand);
        }

        return SuccessResponse::create(
            'carpet_collection_creation',
            $carpetCollection->toArray(),
            'CarpetCollection created successfully'
        );
    }
}
