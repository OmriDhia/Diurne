<?php

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetContactsByCustomer\GetContactsByCustomerQuery;
use App\Contact\Entity\Contact;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetContactsByCustomerController extends CommandQueryController
{
    #[Route('/api/customer/{customerId}/contacts', name: 'get_contacts_by_customer', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get contacts by customer ID',
        content: new Model(type: Contact::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(int $customerId): JsonResponse
    {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getContactsQuery = new GetContactsByCustomerQuery($customerId);

        $response = $this->ask($getContactsQuery);

        return SuccessResponse::create(
            'get_contacts_by_customer',
            $response
        );
    }
}
