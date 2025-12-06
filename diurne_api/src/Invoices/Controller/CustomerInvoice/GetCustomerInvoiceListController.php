<?php

namespace App\Invoices\Controller\CustomerInvoice;

use App\Common\Controller\CommandQueryController;
use App\Invoices\Bus\Query\CustomerInvoice\GetCustomerInvoiceListQuery;
use App\Invoices\Bus\Query\CustomerInvoice\GetCustomerInvoiceListResponse;
use App\Invoices\DTO\GetCustomerInvoiceListRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customerInvoices', name: 'list_customer_invoice', methods: ['GET'])]
class GetCustomerInvoiceListController extends CommandQueryController
{
    #[OA\Response(response: 200, description: 'List', content: new Model(type: GetCustomerInvoiceListResponse::class))]
    #[OA\RequestBody(
        description: 'Filter parameters',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'invoiceNumber', type: 'string'),
                new OA\Property(property: 'contremarque', type: 'string'),
                new OA\Property(property: 'fromDate', type: 'string'),
                new OA\Property(property: 'toDate', type: 'string'),
                new OA\Property(property: 'page', type: 'integer'),
                new OA\Property(property: 'itemsPerPage', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Invoices')]
    public function __invoke(#[MapQueryString] GetCustomerInvoiceListRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('read', 'invoice')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $query = new GetCustomerInvoiceListQuery(
            invoiceNumber: $dto->invoiceNumber,
            contremarque: $dto->contremarque,
            fromDate: $dto->fromDate,
            toDate: $dto->toDate,
            cleared: $dto->cleared,
            page: $dto->page,
            itemsPerPage: $dto->itemsPerPage
        );
        $response = $this->ask($query);
        return new JsonResponse($response->toArray());
    }
}
