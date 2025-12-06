<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use InvalidArgumentException;
use App\Common\Controller\CommandQueryController;
use App\Contact\Bus\Command\Address\UpdateAddressCommand;
use App\Contact\DTO\UpdateAddressRequestDto;
use App\Contact\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Common\Response\SuccessResponse;

final class UpdateAddressController extends CommandQueryController
{
    #[Route('/api/updateAddress/{addressId}', name: 'address_update', methods: ['PUT'])]
    #[OA\Tag(name: 'Contact')]
    #[OA\Response(
        response: 200,
        description: 'Address updated successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'address_update'),
                new OA\Property(property: 'data', ref: new Model(type: Address::class)),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid request data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Invalid request data'),
                new OA\Property(property: 'details', type: 'object', example: ['field' => 'Error message']),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to access this content'),
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Address not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Address with ID 1 not found.'),
            ]
        )
    )]
    #[OA\RequestBody(
        description: 'Address data to update',
        required: true,
        content: new OA\JsonContent(
            ref: new Model(type: UpdateAddressRequestDto::class)
        )
    )]
    public function __invoke(
        #[MapRequestPayload] UpdateAddressRequestDto $requestDTO,
        int                                          $addressId
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        try {
            $command = new UpdateAddressCommand(
                addressId: $addressId,
                fullName: $requestDTO->fullName,
                address1: $requestDTO->address1,
                city: $requestDTO->city,
                zipCode: $requestDTO->zip_code,
                state: $requestDTO->state,
                countryId: (int)$requestDTO->countryId,
                addressTypeId: (int)$requestDTO->addressTypeId,
                isFValide: $requestDTO->isFValide,
                isLValide: $requestDTO->isLValide,
                isWrong: $requestDTO->isWrong,
                comment: $requestDTO->comment,
                phone: $requestDTO->phone,
                mobilePhone: $requestDTO->mobile_phone,
            );

            $addressResponse = $this->handle($command);

            return SuccessResponse::create(
                'address_update',
                $addressResponse->toArray()
            );
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}
