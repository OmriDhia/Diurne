<?php

namespace App\Invoices\Controller\SupplierInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\SupplierInvoice\DeleteSupplierInvoiceCommand;
use App\Invoices\Bus\Command\SupplierInvoice\SupplierInvoiceResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplierInvoices/{id}', name: 'delete_supplier_invoice', methods: ['DELETE'])]
class DeleteSupplierInvoiceController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: SupplierInvoiceResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $command = new DeleteSupplierInvoiceCommand($id);
        $response = $this->handle($command);
        return SuccessResponse::create('supplier_invoice_deleted', $response->toArray());
    }
}
