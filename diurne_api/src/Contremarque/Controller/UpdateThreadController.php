<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Thread\UpdateThreadCommand;
use App\Contremarque\DTO\UpdateThreadRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateThreadController extends CommandQueryController
{
    #[Route('/api/Thread/{threadId}/update', name: 'thread_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Thread updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateThreadRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['threadNumber', 'techColorId'],
            properties: [
                new OA\Property(property: 'threadNumber', type: 'int'),
                new OA\Property(property: 'techColorId', type: 'int'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $threadId,
        #[MapRequestPayload] UpdateThreadRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCommand = new UpdateThreadCommand(
            $threadId,
            $updateDto->threadNumber,
            $updateDto->techColorId,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'thread_update',
            $response->toArray()
        );
    }
}
