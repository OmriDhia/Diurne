<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Thread\CreateThreadCommand;
use App\Contremarque\DTO\CreateThreadRequestDto;
use App\Contremarque\Entity\Thread;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/CarpetComposition/{carpetCompositionId}/Thread/create', name: 'thread_creation', methods: ['POST'])]
class CreateThreadController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Thread creation',
        content: new Model(type: Thread::class)
    )]
    #[OA\RequestBody(
        description: 'Thread data',
        content: new OA\JsonContent(
            required: ['threadNumber', 'techColorId'],
            properties: [
                new OA\Property(property: 'threadNumber', type: 'int'),
                new OA\Property(property: 'techColorId', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $carpetCompositionId,
        #[MapRequestPayload] CreateThreadRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateThreadCommand(
            $carpetCompositionId,
            $requestDTO->threadNumber,
            $requestDTO->techColorId,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'thread_creation',
            $response->toArray(),
            'Thread created successfully',
        );
    }
}
