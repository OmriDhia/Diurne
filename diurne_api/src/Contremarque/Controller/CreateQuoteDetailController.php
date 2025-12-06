<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateQuoteCarpetSpecification\CreateQuoteCarpetSpecificationCommand;
use App\Contremarque\Bus\Command\CreateQuoteDetail\CreateQuoteDetailCommand;
use App\Contremarque\DTO\CreateQuoteDetailWithCarpetSpecificationRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuoteDetailController extends CommandQueryController
{
    #[Route(
        '/api/Quote/{quoteId}/createQuoteDetail',
        name: 'quote_detail_creation',
        methods: ['POST']
    )]
    #[OA\Post(
        path: '/api/Quote/{quoteId}/createQuoteDetail',
        tags: ['Devis'],
        summary: 'Creates a Quote Detail and its associated Carpet Specification',
        requestBody: new OA\RequestBody(
            description: 'Quote Detail and Carpet Specification data',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: new Model(type: CreateQuoteDetailWithCarpetSpecificationRequestDto::class))
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Quote Detail and Carpet Specification created',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'quoteDetail', type: 'object'),
                            new OA\Property(property: 'carpetSpecification', type: 'object'),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function __invoke(
        int                                                                     $quoteId,
        #[MapRequestPayload] CreateQuoteDetailWithCarpetSpecificationRequestDto $payload
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Extract Quote Detail data
        $quoteDetailData = $payload->quoteDetail;
        $carpetSpecificationData = $payload->carpetSpecification;

        // Create QuoteDetail
        $createQuoteDetailCommand = new CreateQuoteDetailCommand(
            $quoteId,
            $quoteDetailData->locationId,
            $quoteDetailData->TarifId,
            $quoteDetailData->estimatedDeliveryTime,
            $quoteDetailData->isValidated,
            $quoteDetailData->totalPriceRate,
            $quoteDetailData->validatedAt ?? null,
            $quoteDetailData->wantedQuantity,
            $quoteDetailData->reference,
            $quoteDetailData->applyLargeProjectRate,
            $quoteDetailData->applyProposedDiscount,
            $quoteDetailData->proposedDiscountRate,
            $quoteDetailData->calculateFromTotalExcludingTax,
            $quoteDetailData->currencyId,
            $quoteDetailData->inStockCarpet,
            $quoteDetailData->comment,
            $quoteDetailData->rn,
            $quoteDetailData->specificTreatmentIds,

        );
        $quoteDetailResponse = $this->handle($createQuoteDetailCommand);

        // Create CarpetSpecification
        $createCarpetSpecificationCommand = new CreateQuoteCarpetSpecificationCommand(
            $quoteDetailResponse->getId(),
            $carpetSpecificationData
        );
        $carpetSpecificationResponse = $this->handle($createCarpetSpecificationCommand);

        return SuccessResponse::create(
            'quote_detail_creation',
            [
                'quoteDetail' => $quoteDetailResponse->toArray(),
                'carpetSpecification' => $carpetSpecificationResponse->toArray(),
            ]
        );
    }
}
