<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Bus\Query\QueryResponse;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetContremarquesByCustomerId\GetContremarquesByCustomerIdQuery;
use App\Contremarque\Entity\Contremarque;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetContremarquesByCustomerIdController extends CommandQueryController
{
    #[Route('/api/customer/{customerId}/contremarques', name: 'get_contremarques_by_customer_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Contremarques retrieval by Customer ID',
        content: new Model(type: Contremarque::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $customerId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetContremarquesByCustomerIdQuery($customerId);
        $response = $this->ask($query);

        return SuccessResponse::create('get_contremarques_by_customer_id', $response->toArray());
    }
}
