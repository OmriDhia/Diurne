<?php

namespace App\Invoices\Controller\SupplierInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\CreateSupplierInvoiceDetailCommand;
use App\Invoices\Bus\Command\SupplierInvoiceDetail\SupplierInvoiceDetailResponse;
use App\Invoices\DTO\SupplierInvoiceDetail\CreateSupplierInvoiceDetailRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplier-invoice-details/create', name: 'create_supplier_invoice_detail', methods: ['POST'])]
class CreateSupplierInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: SupplierInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapRequestPayload] CreateSupplierInvoiceDetailRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateSupplierInvoiceDetailCommand(
            supplierInvoiceId: $dto->supplierInvoiceId,
            rnId: $dto->rnId,
            carpetNumber: $dto->carpetNumber,
            pricePerSquareMeter: $dto->pricePerSquareMeter,
            invoiceSurface: $dto->invoiceSurface,
            invoiceAmount: $dto->invoiceAmount,
            theoreticalPrice: $dto->theoreticalPrice,
            penalty: $dto->penalty,
            producedSurface: $dto->producedSurface,
            actualCreditAmount: $dto->actualCreditAmount,
            theoreticalCredit: $dto->theoreticalCredit,
            finalCarpetAmount: $dto->finalCarpetAmount,
            weight: $dto->weight,
            weightPercentage: $dto->weightPercentage,
            freight: $dto->freight,
            cleared: $dto->cleared
        );

        try {
            $response = $this->handle($command);
            return SuccessResponse::create('supplier_invoice_detail_created', $response->toArray(), '', 201);
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
