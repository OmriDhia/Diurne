<?php

namespace App\Invoices\Controller\SupplierInvoice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Invoices\Bus\Command\SupplierInvoice\CreateSupplierInvoiceCommand;
use App\Invoices\Bus\Command\SupplierInvoice\SupplierInvoiceResponse;
use App\Invoices\DTO\CreateSupplierInvoiceRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplierInvoices', name: 'create_supplier_invoice', methods: ['POST'])]
class CreateSupplierInvoiceController extends CommandQueryController
{
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: SupplierInvoiceResponse::class))]
    #[OA\RequestBody(
        description: 'Supplier invoice data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'invoiceDate', type: 'string', format: 'date-time'),
                new OA\Property(property: 'manufacturerId', type: 'integer'),
                new OA\Property(property: 'packingList', type: 'string'),
                new OA\Property(property: 'airWay', type: 'string'),
                new OA\Property(property: 'fretTotal', type: 'string'),
                new OA\Property(property: 'currencyId', type: 'integer'),
                new OA\Property(property: 'authorId', type: 'integer'),
                new OA\Property(property: 'amountOther', type: 'string'),
                new OA\Property(property: 'weight', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
                new OA\Property(property: 'isincluded', type: 'boolean'),
                new OA\Property(property: 'weightTotal', type: 'string'),
                new OA\Property(property: 'surfaceTotal', type: 'string'),
                new OA\Property(property: 'invoiceTotal', type: 'string'),
                new OA\Property(property: 'theoreticalTotal', type: 'string'),
                new OA\Property(property: 'amountTheoretical', type: 'string'),
                new OA\Property(property: 'amountReal', type: 'string'),
                new OA\Property(property: 'creditNumber', type: 'integer'),
                new OA\Property(property: 'creditDate', type: 'string', format: 'date-time'),
                new OA\Property(property: 'paymentReal', type: 'string'),
                new OA\Property(property: 'paymentTheoretical', type: 'string'),
                new OA\Property(property: 'paymentDate', type: 'string', format: 'date-time'),
            ]
        )
    )]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapRequestPayload] CreateSupplierInvoiceRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $command = new CreateSupplierInvoiceCommand(
            invoiceNumber: $dto->invoiceNumber,
            invoiceDate: $dto->invoiceDate,
            manufacturerId: $dto->manufacturerId,
            packingList: $dto->packingList,
            airWay: $dto->airWay,
            fretTotal: $dto->fretTotal,
            currencyId: $dto->currencyId,
            authorId: $dto->authorId,
            amountOther: $dto->amountOther,
            weight: $dto->weight,
            description: $dto->description,
            isincluded: $dto->isincluded,
            weightTotal: $dto->weightTotal,
            surfaceTotal: $dto->surfaceTotal,
            invoiceTotal: $dto->invoiceTotal,
            theoreticalTotal: $dto->theoreticalTotal,
            amountTheoretical: $dto->amountTheoretical,
            amountReal: $dto->amountReal,
            creditNumber: $dto->creditNumber,
            creditDate: $dto->creditDate,
            paymentReal: $dto->paymentReal,
            paymentTheoretical: $dto->paymentTheoretical,
            paymentDate: $dto->paymentDate
        );
        $response = $this->handle($command);
        return SuccessResponse::create('supplier_invoice_created', $response->toArray(), '', JsonResponse::HTTP_CREATED);
    }
}
