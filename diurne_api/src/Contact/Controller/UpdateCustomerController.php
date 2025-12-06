<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\UpdateCustomerCommand;
use App\Contact\DTO\UpdateCustomerRequestDto;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCustomerController extends CommandQueryController
{
    #[Route('/api/updateCustomer/{customerId}', name: 'contact_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Customer update',
        content: new Model(type: Customer::class)
    )]
    #[OA\RequestBody(
        description: 'Customer data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'social_reason', type: 'string'),
                new OA\Property(property: 'tva_ce', type: 'string'),
                new OA\Property(property: 'website', type: 'string'),
                new OA\Property(property: 'discountTypeId', type: 'integer'),
                new OA\Property(property: 'customerGroupId', type: 'integer'),
                new OA\Property(property: 'firstname', type: 'string'),
                new OA\Property(property: 'lastname', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'gender_id', type: 'integer'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'mobile_phone', type: 'string'),
                new OA\Property(property: 'fax', type: 'string'),
                new OA\Property(property: 'is_agent', type: 'bool'),
                new OA\Property(property: 'contact_origin_id', type: 'integer',),
                new OA\Property(property: 'commentaire', type: 'string'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] UpdateCustomerRequestDto $requestDTO,
                                                      $customerId
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCustomerCommand = new UpdateCustomerCommand($customerId);
        $updateCustomerCommand->setSocialReason($requestDTO->social_reason);
        $updateCustomerCommand->setTvaCe($requestDTO->tva_ce);
        $updateCustomerCommand->setWebsite($requestDTO->website);
        $updateCustomerCommand->setDiscountTypeId($requestDTO->discountTypeId);
        $updateCustomerCommand->setCustomerGroupId($requestDTO->customerGroupId);
        $updateCustomerCommand->setFirstname($requestDTO->firstname ?? null);
        $updateCustomerCommand->setLastname($requestDTO->lastname ?? null);
        $updateCustomerCommand->setEmail($requestDTO->email ?? null);
        $updateCustomerCommand->setGenderId($requestDTO->gender_id ?? null);
        $updateCustomerCommand->setPhone($requestDTO->phone ?? null);
        $updateCustomerCommand->setMobilePhone($requestDTO->mobile_phone ?? null);
        $updateCustomerCommand->setFax($requestDTO->fax ?? null);
        $updateCustomerCommand->setIsAgent($requestDTO->is_agent ?? null);
        $updateCustomerCommand->setContactOriginId($requestDTO->contact_origin_id);
        $updateCustomerCommand->setCommentaire($requestDTO->commentaire);
        $contactResponse = $this->handle($updateCustomerCommand);


        return SuccessResponse::create(
            'contact_update',
            $contactResponse->toArray()
        );
    }
}
