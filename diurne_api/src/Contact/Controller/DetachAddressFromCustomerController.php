<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\DetachAddressFromCustomerCommand;
use App\Contact\DTO\DetachAddressFromCustomerRequestDto;
use App\Contact\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class DetachAddressFromCustomerController extends CommandQueryController
{
    #[Route('/api/detachAddressFromCustomer', name: 'detach_address_from_customer', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Detach Address from Contact',
        content: new Model(type: Address::class)
    )]
    #[OA\RequestBody(
        description: 'Address to Contact data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'addressId', type: 'int'),
                new OA\Property(property: 'contactId', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] DetachAddressFromCustomerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $detachAddressFromCustomerCommand = new DetachAddressFromCustomerCommand($requestDTO->addressId, $requestDTO->customerId);
        $assignAddressToCustomer = $this->handle($detachAddressFromCustomerCommand);

        return SuccessResponse::create(
            'detach_address_from_customer',
            $assignAddressToCustomer->toArray()
        );
    }
}
