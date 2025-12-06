<?php

namespace App\Contremarque\Controller\ImageCommand;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ImageCommand\ImageCommandResponse;
use App\Contremarque\Bus\Command\ImageCommand\UpdateImageCommandCommand;
use App\Contremarque\DTO\UpdateImageCommandRequesDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateImageCommandController extends CommandQueryController
{
    #[Route(
        '/api/image-command/{id}',
        name: 'update_image_command',
        methods: ['PUT', 'PATCH']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update image command',
        content: new Model(type: ImageCommandResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'commercialComment', type: 'string'),
                new OA\Property(property: 'advComment', type: 'string'),
                new OA\Property(property: 'commandNumber', type: 'string'),
                new OA\Property(property: 'rn', type: 'string'),
                new OA\Property(property: 'studioComment', type: 'string'),
                new OA\Property(property: 'status_id', type: 'integer'),

            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int                                              $id,
        #[MapRequestPayload] UpdateImageCommandRequesDto $requestDTO,
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateImageCommandCommand = new UpdateImageCommandCommand(
            $id,
            $requestDTO->commandNumber,
            $requestDTO->commercialComment,
            $requestDTO->advComment,
            $requestDTO->rn,
            $requestDTO->studioComment,
            (int)$requestDTO->status_id,
        );

        $imageResponse = $this->handle($updateImageCommandCommand);

        return SuccessResponse::create(
            'update_image_command',
            $imageResponse->toArray()

        );
    }
}
