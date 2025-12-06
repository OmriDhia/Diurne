<?php

namespace App\Invoices\Controller\SupplierInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\DeleteSupplierInvoiceDetailCommand;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\SupplierInvoiceDetailResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplier-invoice-details/{id}', name: 'delete_supplier_invoice_detail', methods: ['DELETE'])]
class DeleteSupplierInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: SupplierInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteSupplierInvoiceDetailCommand($id);
        try {
            $response = $this->handle($command);
            return SuccessResponse::create('supplier_invoice_detail_deleted', $response->toArray());
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
