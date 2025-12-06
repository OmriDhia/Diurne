<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\AssignAddressToCustomerCommand;
use App\Contact\DTO\AssignAddressToCustomerRequestDto;
use App\Contact\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AssignAddressToCustomerController extends CommandQueryController
{
    #[Route('/api/AssignAddressToCustomer', name: 'assign_address_to_customer', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Assign Address to Contact',
        content: new Model(type: Address::class)
    )]
    #[OA\RequestBody(
        description: 'Address to Contact data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'addressId', type: 'int'),
                new OA\Property(property: 'contactId', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] AssignAddressToCustomerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $assignAddressToCustomerCommand = new AssignAddressToCustomerCommand($requestDTO->addressId, $requestDTO->customerId);
        $assignAddressToCustomer = $this->handle($assignAddressToCustomerCommand);

        return SuccessResponse::create(
            'assign_address_to_customer',
            $assignAddressToCustomer->toArray(),
            'Address assigned to Contact successfully'

        );
    }
}
