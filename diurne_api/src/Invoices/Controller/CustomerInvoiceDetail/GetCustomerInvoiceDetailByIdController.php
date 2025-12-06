<?php

namespace App\Invoices\Controller\CustomerInvoiceDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\CustomerInvoiceDetail\GetCustomerInvoiceDetailByIdQuery;
use App\Invoices\Bus\Query\CustomerInvoiceDetail\GetCustomerInvoiceDetailByIdResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer-invoice-details/{id}', name: 'get_customer_invoice_detail_by_id', methods: ['GET'])]
class GetCustomerInvoiceDetailByIdController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Customer invoice detail', content: new Model(type: GetCustomerInvoiceDetailByIdResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetCustomerInvoiceDetailByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('customer_invoice_detail_by_id', $response->toArray());
    }
}
