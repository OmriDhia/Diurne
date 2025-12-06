<?php

namespace App\Invoices\Controller\CustomerInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\CustomerInvoice\GetCustomerInvoiceByIdQuery;
use App\Invoices\Bus\Query\CustomerInvoice\GetCustomerInvoiceByIdResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customerInvoices/{id}', name: 'get_customer_invoice_by_id', methods: ['GET'])]
class GetCustomerInvoiceByIdController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Customer invoice', content: new Model(type: GetCustomerInvoiceByIdResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $query = new GetCustomerInvoiceByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::get('customer_invoice_by_id', $response->toArray());
    }
}
