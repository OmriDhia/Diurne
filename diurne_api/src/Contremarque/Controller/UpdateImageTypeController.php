<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetDesignOrderImages\UpdateImageTypeCommand;
use App\Contremarque\DTO\UpdateImageTypeRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UpdateImageTypeController extends CommandQueryController
{
    #[Route(
        '/api/image/update-type',
        name: 'update_image_type',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update Image Type',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageId', type: 'integer'),
                new OA\Property(property: 'imageTypeId', type: 'integer'),
            ]
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageId', type: 'integer', description: 'The ID of the image to update'),
                new OA\Property(property: 'imageTypeId', type: 'integer', description: 'The ID of the new image type')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request): JsonResponse
    {
        // Extract the ids from the request body
        $data = json_decode($request->getContent(), true);

        if (empty($data['imageId']) || empty($data['imageTypeId'])) {
            return new JsonResponse(['error' => 'Both imageId and imageTypeId are required.'], 400);
        }

        $updateImageTypeRequest = new UpdateImageTypeRequest($data['imageId'], $data['imageTypeId']);
        $command = new UpdateImageTypeCommand($updateImageTypeRequest->getImageId(), $updateImageTypeRequest->getImageTypeId());
        $response = $this->handle($command);

        return SuccessResponse::create(
            'update_image_type',
            $response->toArray(),
            'Image type updated successfully'
        );
    }
}
