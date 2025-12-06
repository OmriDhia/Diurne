<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ImageType\CreateImageTypeCommand;
use App\Setting\DTO\CreateImageTypeRequestDto;
use App\Setting\Entity\ImageType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-type/create', name: 'image_type_creation', methods: ['POST'])]
class CreateImageTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'ImageType creation',
        content: new Model(type: ImageType::class)
    )]
    #[OA\RequestBody(
        description: 'ImageType data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'description', type: 'string', nullable: true),
                new OA\Property(property: 'category', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateImageTypeRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $createCommand = new CreateImageTypeCommand(
            $requestDTO->name,
            $requestDTO->description,
            $requestDTO->category
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'image_type_creation',
            $response->toArray()
        );
    }
}
