<?php

namespace App\Invoices\Controller\SupplierInvoice;

use App\Common\Controller\CommandQueryController;
use App\Invoices\Bus\Query\SupplierInvoice\GetSupplierInvoiceListQuery;
use App\Invoices\Bus\Query\SupplierInvoice\GetSupplierInvoiceListResponse;
use App\Invoices\DTO\GetSupplierInvoiceListRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/supplierInvoices', name: 'list_supplier_invoice', methods: ['GET'])]
class GetSupplierInvoiceListController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'List', content: new Model(type: GetSupplierInvoiceListResponse::class))]
    #[OA\RequestBody(
        description: 'Filter parameters',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'invoiceNumber', type: 'string'),
                new OA\Property(property: 'authorId', type: 'integer'),
                new OA\Property(property: 'rn', type: 'string'),
                new OA\Property(property: 'dateFrom', type: 'string', format: 'date'),
                new OA\Property(property: 'dateTo', type: 'string', format: 'date'),
                new OA\Property(property: 'orderBy', type: 'string'),
                new OA\Property(property: 'orderWay', type: 'string'),
                new OA\Property(property: 'page', type: 'integer'),
                new OA\Property(property: 'itemsPerPage', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapQueryString] GetSupplierInvoiceListRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $query = new GetSupplierInvoiceListQuery(
            invoiceNumber: $dto->invoiceNumber,
            authorId: $dto->authorId,
            page: $dto->page,
            itemsPerPage: $dto->itemsPerPage,
            orderBy: $dto->orderBy,
            orderWay: $dto->orderWay,
            rn: $dto->rn,
            dateFrom: $dto->dateFrom,
            dateTo: $dto->dateTo
        );
        $response = $this->ask($query);
        return new JsonResponse($response->toArray());
    }
}
