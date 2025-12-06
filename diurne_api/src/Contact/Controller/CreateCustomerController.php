<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\CreateCustomerCommand;
use App\Contact\DTO\CreateCustomerRequestDto;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCustomerController extends CommandQueryController
{
    #[Route('/api/createCustomer', name: 'customer_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Customer creation',
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
                new OA\Property(property: 'contact_origin_id', type: 'integer'),
                new OA\Property(property: 'commentaire', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateCustomerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCustomerCommand = new CreateCustomerCommand();
        $createCustomerCommand->setSocialReason($requestDTO->social_reason ?? null);
        $createCustomerCommand->setTvaCe($requestDTO->tva_ce ?? null);
        $createCustomerCommand->setWebsite($requestDTO->website ?? null);
        $createCustomerCommand->setDiscountTypeId($requestDTO->discountTypeId);
        $createCustomerCommand->setCustomerGroupId($requestDTO->customerGroupId);
        $createCustomerCommand->setMailingLanguageId($requestDTO->mailingLanguageId);
        $createCustomerCommand->setFirstname($requestDTO->firstname ?? null);
        $createCustomerCommand->setLastname($requestDTO->lastname ?? null);
        $createCustomerCommand->setEmail($requestDTO->email ?? null);
        $createCustomerCommand->setGenderId($requestDTO->gender_id ?? null);
        $createCustomerCommand->setPhone($requestDTO->phone ?? null);
        $createCustomerCommand->setMobilePhone($requestDTO->mobile_phone ?? null);
        $createCustomerCommand->setFax($requestDTO->fax ?? null);
        $createCustomerCommand->setIsAgent($requestDTO->is_agent ?? null);
        $createCustomerCommand->setContactOriginId($requestDTO->contact_origin_id);
        $createCustomerCommand->setCommentaire($requestDTO->commentaire);
        $customerResponse = $this->handle($createCustomerCommand);


        return SuccessResponse::create(
            'contact_creation',
            $customerResponse->toArray(),
            'Contact created successfully.'

        );
    }
}
