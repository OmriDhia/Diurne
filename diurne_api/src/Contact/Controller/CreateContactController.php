<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\CreateContactCommand;
use App\Contact\DTO\CreateContactRequestDto;
use App\Contact\Entity\Contact;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateContactController extends CommandQueryController
{
    #[Route('/api/createContact/{customerId}', name: 'contact_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Contact creation',
        content: new Model(type: Contact::class)
    )]
    #[OA\RequestBody(
        description: 'Contact data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'gender_id', type: 'integer'),
                new OA\Property(property: 'firstname', type: 'string'),
                new OA\Property(property: 'lastname', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'mailing', type: 'boolean'),
                new OA\Property(property: 'mailing_with_calligraphie', type: 'boolean'),
                new OA\Property(property: 'phone', type: 'string'),
                new OA\Property(property: 'mobile_phone', type: 'string'),
                new OA\Property(property: 'fax', type: 'string'),

            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateContactRequestDto $requestDTO,
        $customerId
    ): JsonResponse {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createContactCommand = new CreateContactCommand($customerId);
        $createContactCommand->setGenderId($requestDTO->gender_id);
        $createContactCommand->setFirstname($requestDTO->firstname);
        $createContactCommand->setLastName($requestDTO->lastname);
        $createContactCommand->setEmail($requestDTO->email);
        $createContactCommand->setMailing($requestDTO->mailing);
        $createContactCommand->setMailingWithCalligraphie($requestDTO->mailing_with_calligraphie);
        $createContactCommand->setPhone($requestDTO->phone);
        $createContactCommand->setMobilePhone($requestDTO->mobile_phone);
        $createContactCommand->setFax($requestDTO->fax);
        $contactResponse = $this->handle($createContactCommand);


        return SuccessResponse::create(
            'contact_creation',
            $contactResponse->toArray(),
            'Contact created successfully.'

        );
    }
}
