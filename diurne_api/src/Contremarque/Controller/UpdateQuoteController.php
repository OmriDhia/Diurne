<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateQuote\UpdateQuoteCommand;
use App\Contremarque\DTO\UpdateQuoteRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateQuoteController extends CommandQueryController
{
    #[Route('/api/contremarque/{contremarqueId}/quote/{quoteId}', name: 'quote_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update quote',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'discountRuleId', type: 'integer'),
            new OA\Property(property: 'taxRuleId', type: 'integer'),
            new OA\Property(property: 'currencyId', type: 'integer'),
            new OA\Property(property: 'languageId', type: 'integer'),
            new OA\Property(property: 'unitOfMeasurement', type: 'string'),
            new OA\Property(property: 'deliveryAddressId', type: 'integer'),
            new OA\Property(property: 'invoiceAddressId', type: 'integer'),
            new OA\Property(property: 'withoutDiscountPrice', type: 'float'),
            new OA\Property(property: 'additionalDiscount', type: 'float'),
            new OA\Property(property: 'totalDiscountAmount', type: 'float'),
            new OA\Property(property: 'totalDiscountPercentage', type: 'float'),
            new OA\Property(property: 'totalTaxExcluded', type: 'float'),
            new OA\Property(property: 'shippingPrice', type: 'float'),
            new OA\Property(property: 'tax', type: 'float'),
            new OA\Property(property: 'totalTaxIncluded', type: 'float'),
            new OA\Property(property: 'quoteSentToCustomer', type: 'boolean'),
            new OA\Property(property: 'qualificationMessage', type: 'string'),
            new OA\Property(property: 'conversionId', type: 'integer'),
            new OA\Property(property: 'transportConditionId', type: 'integer'),
            new OA\Property(property: 'weight', type: 'float'),
        ])
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        int $contremarqueId,
        int $quoteId,
        #[MapRequestPayload] UpdateQuoteRequestDto $requestDTO
    ): JsonResponse {
        // Authorization check
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Create and handle the command
        $updateQuoteCommand = new UpdateQuoteCommand($contremarqueId, $quoteId, $requestDTO);
        $response = $this->handle($updateQuoteCommand);

        return SuccessResponse::create(
            'quote_update',
            $response->toArray(),
            'quote update is successful'
        );
    }
}
