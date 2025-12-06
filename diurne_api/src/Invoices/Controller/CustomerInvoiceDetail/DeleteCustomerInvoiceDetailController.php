<?php

namespace App\Invoices\Controller\CustomerInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\DeleteCustomerInvoiceDetailCommand;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\CustomerInvoiceDetailResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer-invoice-details/{id}', name: 'delete_customer_invoice_detail', methods: ['DELETE'])]
class DeleteCustomerInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: CustomerInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteCustomerInvoiceDetailCommand($id);
        try {
            $response = $this->handle($command);
            return SuccessResponse::create('customer_invoice_detail_deleted', $response->toArray());
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
