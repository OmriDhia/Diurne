<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\DeleteCustomer\DeleteCustomerCommand;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteCustomerController extends CommandQueryController
{
    #[Route('/api/customer/{customerId}/delete', name: 'customer_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Customer delete',
        content: new Model(type: Customer::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $customerId
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started

        $user = $session->get('user');

        $deleteCustomerCommand = new DeleteCustomerCommand(
            (int) $customerId,
            $user
        );
        $customerResponse = $this->handle($deleteCustomerCommand);

        return SuccessResponse::create(
            'customer_delete',
            $customerResponse->toArray()
        );
    }
}
