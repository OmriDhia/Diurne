<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Carrier\UpdateCarrierCommand;
use App\Setting\DTO\UpdateCarrierRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCarrierController extends CommandQueryController
{
    #[Route('/api/updateCarrier/{id}', name: 'carrier_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Carrier updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateCarrierRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
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
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateCarrierRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateCarrierCommand(
            $id,
            $updateDto->name,
            $updateDto->contact,
            $updateDto->email,
            $updateDto->phone,
            $updateDto->fax,
            $updateDto->address
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'carrier_update',
            $response->toArray()
        );
    }
}
