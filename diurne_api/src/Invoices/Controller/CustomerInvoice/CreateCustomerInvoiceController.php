<?php

namespace App\Invoices\Controller\CustomerInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\CustomerInvoice\CreateCustomerInvoiceCommand;
use App\Invoices\Bus\Command\CustomerInvoice\CustomerInvoiceResponse;
use App\Invoices\DTO\CreateCustomerInvoiceRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customerInvoices', name: 'create_customer_invoice', methods: ['POST'])]
class CreateCustomerInvoiceController extends CommandQueryController
{
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: CustomerInvoiceResponse::class))]
    #[OA\RequestBody(
        description: 'Customer invoice data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'invoiceDate', type: 'string', format: 'date-time'),
                new OA\Property(property: 'invoiceType', type: 'integer'),
                new OA\Property(property: 'carrierId', type: 'integer'),
                new OA\Property(property: 'customerId', type: 'integer'),
                new OA\Property(property: 'carpetOrderId', type: 'integer', nullable: true),
                new OA\Property(property: 'quantityTotal', type: 'integer'),
                new OA\Property(property: 'shippingCostsHt', type: 'string'),
                new OA\Property(property: 'billed', type: 'string'),
                new OA\Property(property: 'payment', type: 'string'),
                new OA\Property(property: 'totalHt', type: 'string'),
                new OA\Property(property: 'amountHt', type: 'string'),
                new OA\Property(property: 'amountTva', type: 'string'),
                new OA\Property(property: 'amountTtc', type: 'string'),
                new OA\Property(property: 'prescriberId', type: 'integer', nullable: true),
                new OA\Property(property: 'invoiceTypeEntityId', type: 'integer', nullable: true),
                new OA\Property(property: 'currencyId', type: 'integer', nullable: true),
                new OA\Property(property: 'conversionId', type: 'integer', nullable: true),
                new OA\Property(property: 'languageId', type: 'integer', nullable: true),
                new OA\Property(property: 'mesurementId', type: 'integer', nullable: true),
                new OA\Property(property: 'regulationId', type: 'integer', nullable: true),
                new OA\Property(property: 'tarifExpeditionId', type: 'integer', nullable: true),
                new OA\Property(property: 'rnId', type: 'integer', nullable: true),
                new OA\Property(property: 'taxRuleId', type: 'integer', nullable: true),
                new OA\Property(property: 'description', type: 'string', nullable: true),
                new OA\Property(property: 'number', type: 'string', nullable: true),
                new OA\Property(property: 'project', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapRequestPayload] CreateCustomerInvoiceRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $command = new CreateCustomerInvoiceCommand(
            invoiceDate: $dto->invoiceDate,
            invoiceType: $dto->invoiceType,
            carrierId: $dto->carrierId,
            customerId: $dto->customerId,
            carpetOrderId: $dto->carpetOrderId,
            quantityTotal: $dto->quantityTotal,
            shippingCostsHt: $dto->shippingCostsHt,
            billed: $dto->billed,
            payment: $dto->payment,
            totalHt: $dto->totalHt,
            amountHt: $dto->amountHt,
            amountTva: $dto->amountTva,
            amountTtc: $dto->amountTtc,
            prescriberId: $dto->prescriberId,
            invoiceTypeEntityId: $dto->invoiceTypeEntityId,
            currencyId: $dto->currencyId,
            conversionId: $dto->conversionId,
            languageId: $dto->languageId,
            mesurementId: $dto->mesurementId,
            regulationId: $dto->regulationId,
            tarifExpeditionId: $dto->tarifExpeditionId,
            rnId: $dto->rnId,
            taxRuleId: $dto->taxRuleId,
            description: $dto->description,
            number: $dto->number,
            project: $dto->project
        );
        $response = $this->handle($command);
        return SuccessResponse::create('customer_invoice_created', $response->toArray(), '', JsonResponse::HTTP_CREATED);
    }
}
