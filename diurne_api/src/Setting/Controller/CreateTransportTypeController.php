<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TransportType\CreateTransportTypeCommand;
use App\Setting\DTO\CreateTransportTypeRequestDto;
use App\Setting\Entity\TransportType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transportType/create', name: 'transportType_creation', methods: ['POST'])]
class CreateTransportTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'TransportType creation',
        content: new Model(type: TransportType::class)
    )]
    #[OA\RequestBody(
        description: 'TransportType data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateTransportTypeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateTransportTypeCommand(
            $requestDTO->name,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'transportType_creation',
            $response->toArray()
        );
    }
}
