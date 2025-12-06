<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\OrderPayment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\OrderPayment\GetAllOrderPaymentQuery;
use App\Contremarque\DTO\OrderPayment\GetAllOrderPaymentRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/order-payments', name: 'get_order_payments', methods: ['GET'])]
class GetAllOrderPaymentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch paginated list of order payments',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            // Add your properties here based on OrderPayment entity
                        ]
                    )
                ),
                new OA\Property(
                    property: 'meta',
                    properties: [
                        new OA\Property(property: 'total', type: 'integer'),
                        new OA\Property(property: 'pages', type: 'integer')
                    ],
                    type: 'object'
                )
            ]
        )
    )]
    #[OA\Tag(name: 'OrderPayment')]
    public function __invoke(
        #[MapQueryString] GetAllOrderPaymentRequestDto $requestDto
    ): JsonResponse
    {
        // Normalize orderBy aliases and orderWay
        $orderByMap = [
            'createdAt' => 'created_at',
            'dateOfReceipt' => 'date_of_receipt',
            'paymentAmountHt' => 'payment_amount_ht',
        ];
        $orderBy = $requestDto->orderBy;
        if (null !== $orderBy && array_key_exists($orderBy, $orderByMap)) {
            $orderBy = $orderByMap[$orderBy];
        }
        $orderWay = $requestDto->orderWay ? strtoupper($requestDto->orderWay) : null;

        $query = new GetAllOrderPaymentQuery(
            page: $requestDto->page,
            itemsPerPage: $requestDto->itemsPerPage,
            customer: $requestDto->customer,
            commercial: $requestDto->commercial,
            minPaymentAmount: $requestDto->minPaymentAmount,
            maxPaymentAmount: $requestDto->maxPaymentAmount,
            currency: $requestDto->currency,
            hasNoChilds: $requestDto->hasNoChilds,
            carpetOrderId: $requestDto->carpetOrderId,
            orderBy: $orderBy,
            orderWay: $orderWay,
        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_order_payments',
            $response->toArray()
        );
    }
}