<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Address\CreateAddressCommand;
use App\Contact\DTO\CreateAddressRequestDto;
use App\Contact\Entity\Address;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateAddressController extends CommandQueryController
{
    #[Route('/api/createAddress', name: 'address_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Address creation',
        content: new Model(type: Address::class)
    )]
    #[OA\RequestBody(
        description: 'Address data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'fullName', type: 'string'),
                new OA\Property(property: 'address1', type: 'string'),
                new OA\Property(property: 'city', type: 'string'),
                new OA\Property(property: 'zip_code', type: 'string'),
                new OA\Property(property: 'state', type: 'string'),
                new OA\Property(property: 'is_f_valide', type: 'string'),
                new OA\Property(property: 'is_l_valide', type: 'string'),
                new OA\Property(property: 'is_wrong', type: 'string'),
                new OA\Property(property: 'comment', type: 'string'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'mobile_phone', type: 'string'),
                new OA\Property(property: 'addressTypeId', type: 'integer'),
                new OA\Property(property: 'countryId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateAddressRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createAddressCommand = new CreateAddressCommand();
        $createAddressCommand->setFulName($requestDTO->fullName);
        $createAddressCommand->setAddress1($requestDTO->address1);
        $createAddressCommand->setCity($requestDTO->city);
        $createAddressCommand->setState($requestDTO->state);
        $createAddressCommand->setZipCode($requestDTO->zip_code);
        $createAddressCommand->setIsFValide($requestDTO->isFValide);
        $createAddressCommand->setIsLValide($requestDTO->isLValide);
        $createAddressCommand->setIsWrong($requestDTO->isWrong);
        $createAddressCommand->setComment($requestDTO->comment);
        $createAddressCommand->setPhone($requestDTO->phone);
        $createAddressCommand->setMobilePhone($requestDTO->mobile_phone);
        $createAddressCommand->setCountry((int) $requestDTO->countryId);
        $createAddressCommand->setAddressType((int) $requestDTO->addressTypeId);
        $addressResponse = $this->handle($createAddressCommand);

        return SuccessResponse::create(
            'address_creation',
            $addressResponse->toArray(),
            'Address created successfully'

        );
    }
}
