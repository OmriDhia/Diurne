<?php

namespace App\Invoices\Controller\CustomerInvoiceDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\CustomerInvoiceDetail\GetAllCustomerInvoiceDetailsQuery;
use App\Invoices\Bus\Query\CustomerInvoiceDetail\GetAllCustomerInvoiceDetailsResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer-invoice-details', name: 'get_all_customer_invoice_details', methods: ['GET'])]
class GetAllCustomerInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Customer invoice details', content: new Model(type: GetAllCustomerInvoiceDetailsResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetAllCustomerInvoiceDetailsQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('customer_invoice_details', $response->toArray());
    }
}
