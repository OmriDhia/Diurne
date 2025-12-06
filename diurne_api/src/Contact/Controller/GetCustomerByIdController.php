<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetCustomerById\GetCustomerByIdQuery;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCustomerByIdController extends CommandQueryController
{
    #[Route('/api/customer/{id}', name: 'get_customer_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Customer creation',
        content: new Model(type: Customer::class)
    )]
    #[OA\RequestBody(
        description: 'Customer data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getCustomerByIdQuery = new GetCustomerByIdQuery($id);
        $response = $this->ask($getCustomerByIdQuery);

        return SuccessResponse::create(
            'get_customer_by_id',
            $response
        );
    }
}
