<?php

namespace App\Workshop\Controller\Carpet;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateCarpet\CreateCarpetCommand;
use App\Workshop\DTO\Carpet\CreateCarpetRequestDto;
use App\Workshop\Entity\Carpet;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCarpetController extends CommandQueryController
{
    #[Route('/api/carpet', name: 'carpet_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Carpet created successfully',
        content: new Model(type: Carpet::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet creation data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'manufacturerId', type: 'integer', example: 1),
                new OA\Property(property: 'imageCommandId', type: 'integer', example: 1),
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateCarpetRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'carpet')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $command = new CreateCarpetCommand(
            manufacturerId: $requestDto->manufacturerId,
            imageCommandId: $requestDto->imageCommandId
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'carpet_created',
            $response->toArray(),
            'Carpet created successfully',
            Response::HTTP_CREATED
        );
    }
}