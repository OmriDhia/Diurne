<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetInvoiceTypes\GetInvoiceTypesQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetInvoiceTypesController extends CommandQueryController
{
    #[Route('/api/invoiceTypes', name: 'get_invoice_types', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'get invoice types')]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $query = new GetInvoiceTypesQuery();
        $response = $this->ask($query);

        return SuccessResponse::create('get_invoice_types', $response->toArray());
    }
}
