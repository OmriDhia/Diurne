<?php

namespace App\Invoices\Controller\SupplierInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Query\SupplierInvoice\GetSupplierInvoiceByIdQuery;
use App\Invoices\Bus\Query\SupplierInvoice\GetSupplierInvoiceByIdResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplierInvoices/{id}', name: 'get_supplier_invoice_by_id', methods: ['GET'])]
class GetSupplierInvoiceByIdController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Supplier invoice', content: new Model(type: GetSupplierInvoiceByIdResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $query = new GetSupplierInvoiceByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::get('supplier_invoice_by_id', $response->toArray());
    }
}
