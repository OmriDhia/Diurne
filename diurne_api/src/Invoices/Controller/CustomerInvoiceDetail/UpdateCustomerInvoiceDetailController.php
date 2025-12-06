<?php

namespace App\Invoices\Controller\CustomerInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\UpdateCustomerInvoiceDetailCommand;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\CustomerInvoiceDetailResponse;
use App\Invoices\DTO\CustomerInvoiceDetail\UpdateCustomerInvoiceDetailRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer-invoice-details/{id}', name: 'update_customer_invoice_detail', methods: ['PUT'])]
class UpdateCustomerInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: CustomerInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateCustomerInvoiceDetailRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = $dto->toUpdateCustomerInvoiceDetailCommand($id);

        try {
            $response = $this->handle($command);
            return SuccessResponse::create('customer_invoice_detail_updated', $response->toArray());
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
