<?php

namespace App\Invoices\Controller\CustomerInvoiceDetail;

use RuntimeException;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\CreateCustomerInvoiceDetailCommand;
use App\Invoices\Bus\Command\CustomerInvoiceDetail\CustomerInvoiceDetailResponse;
use App\Invoices\DTO\CustomerInvoiceDetail\CreateCustomerInvoiceDetailRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer-invoice-details/create', name: 'create_customer_invoice_detail', methods: ['POST'])]
class CreateCustomerInvoiceDetailController extends CommandQueryController
{
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: CustomerInvoiceDetailResponse::class))]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapRequestPayload] CreateCustomerInvoiceDetailRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateCustomerInvoiceDetailCommand(
            customerInvoiceId: $dto->customerInvoiceId,
            carpetOrderDetailId: $dto->carpetOrderDetailId,
            cleared: $dto->cleared,
            refCommand: $dto->refCommand,
            refQuote: $dto->refQuote,
            rn: $dto->rn,
            collectionId: $dto->collectionId,
            modelId: $dto->modelId,
            m2: $dto->m2,
            sqft: $dto->sqft,
            ht: $dto->ht,
            ttc: $dto->ttc
        );

        try {
            $response = $this->handle($command);
            return SuccessResponse::create('customer_invoice_detail_created', $response->toArray(), '', 201);
        } catch (RuntimeException $e) {
            return new JsonResponse(['code' => 400, 'message' => 'Validation errors', 'errors' => [$e->getMessage()]], 400);
        }
    }
}
