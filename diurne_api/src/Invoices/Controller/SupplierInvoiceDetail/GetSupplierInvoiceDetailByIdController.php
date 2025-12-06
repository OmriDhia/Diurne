<?php

namespace App\Invoices\Controller\SupplierInvoiceDetail;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\SupplierInvoiceDetail\GetSupplierInvoiceDetailByIdQuery;
use App\Invoices\Bus\Query\SupplierInvoiceDetail\GetSupplierInvoiceDetailByIdResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplier-invoice-details/{id}', name: 'get_supplier_invoice_detail_by_id', methods: ['GET'])]
class GetSupplierInvoiceDetailByIdController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Supplier invoice detail', content: new Model(type: GetSupplierInvoiceDetailByIdResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetSupplierInvoiceDetailByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('supplier_invoice_detail_by_id', $response->toArray());
    }
}
