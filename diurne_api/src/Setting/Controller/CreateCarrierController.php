<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Carrier\CreateCarrierCommand;
use App\Setting\DTO\CreateCarrierRequestDto;
use App\Setting\Entity\Carrier;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createCarrier', name: 'carrier_creation', methods: ['POST'])]
class CreateCarrierController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Carrier creation',
        content: new Model(type: Carrier::class)
    )]
    #[OA\RequestBody(
        description: 'Carrier data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'contact', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'fax', type: 'string'),
                new OA\Property(property: 'address', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateCarrierRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCarrierCommand(
            $requestDTO->name,
            $requestDTO->contact,
            $requestDTO->email,
            $requestDTO->phone,
            $requestDTO->fax,
            $requestDTO->address
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'carrier_creation',
            $response->toArray()
        );
    }
}
