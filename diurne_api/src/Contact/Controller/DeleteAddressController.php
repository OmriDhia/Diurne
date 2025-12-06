<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\DeleteAddress\DeleteAddressCommand;
use App\Contact\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteAddressController extends CommandQueryController
{
    #[Route('/api/address/{addressId}/delete', name: 'address_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Address delete',
        content: new Model(type: Address::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $addressId
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteAddressCommand = new DeleteAddressCommand(
            (int) $addressId,
        );
        $addressResponse = $this->handle($deleteAddressCommand);

        return SuccessResponse::create(
            'address_delete',
            $addressResponse->toArray(),
            'Address deleted successfully'
        );
    }
}
