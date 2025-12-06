<?php

namespace App\Invoices\Controller\CustomerInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\CustomerInvoice\DeleteCustomerInvoiceCommand;
use App\Invoices\Bus\Command\CustomerInvoice\CustomerInvoiceResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customerInvoices/{id}', name: 'delete_customer_invoice', methods: ['DELETE'])]
class DeleteCustomerInvoiceController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: CustomerInvoiceResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $command = new DeleteCustomerInvoiceCommand($id);
        $response = $this->handle($command);
        return SuccessResponse::create('customer_invoice_deleted', $response->toArray());
    }
}
