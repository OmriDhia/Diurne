<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\AddressType\CreateAddressTypeCommand;
use App\Contact\DTO\CreateAddressTypeRequestDto;
use App\Contact\Entity\AddressType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateAddressTypeController extends CommandQueryController
{
    #[Route('/api/createAddressType', name: 'addressType_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'AddressType creation',
        content: new Model(type: AddressType::class)
    )]
    #[OA\RequestBody(
        description: 'AddressType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateAddressTypeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $createAddressTypeCommand = new CreateAddressTypeCommand($requestDTO->name);
        $addressTypeResponse = $this->handle($createAddressTypeCommand);

        return SuccessResponse::create(
            'addressType_creation',
            $addressTypeResponse->toArray(),
            'AddressType created successfully'

        );
    }
}
