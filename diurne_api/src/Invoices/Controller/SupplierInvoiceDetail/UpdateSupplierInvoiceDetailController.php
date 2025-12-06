<?php

namespace App\Invoices\Controller\SupplierInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\UpdateSupplierInvoiceDetailCommand;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\SupplierInvoiceDetailResponse;
use App\Invoices\DTO\SupplierInvoiceDetail\UpdateSupplierInvoiceDetailRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplier-invoice-details/{id}', name: 'update_supplier_invoice_detail', methods: ['PUT'])]
class UpdateSupplierInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: SupplierInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateSupplierInvoiceDetailRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = $dto->toUpdateSupplierInvoiceDetailCommand($id);

        try {
            $response = $this->handle($command);
            return SuccessResponse::create('supplier_invoice_detail_updated', $response->toArray());
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
