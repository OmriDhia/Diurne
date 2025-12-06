<?php

namespace App\Invoices\Controller\SupplierInvoiceDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\SupplierInvoiceDetail\GetAllSupplierInvoiceDetailsQuery;
use App\Invoices\Bus\Query\SupplierInvoiceDetail\GetAllSupplierInvoiceDetailsResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplier-invoice-details', name: 'get_all_supplier_invoice_details', methods: ['GET'])]
class GetAllSupplierInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Supplier invoice details', content: new Model(type: GetAllSupplierInvoiceDetailsResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetAllSupplierInvoiceDetailsQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('supplier_invoice_details', $response->toArray());
    }
}
