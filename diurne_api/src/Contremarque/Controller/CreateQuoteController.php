<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateQuote\CreateQuoteCommand;
use App\Contremarque\DTO\CreateQuoteRequestDto;
use App\Contremarque\Entity\Quote;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateQuoteController extends CommandQueryController
{
    #[Route('/api/contremarque/{contremarqueId}/createQuote', name: 'quote_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Quote creation',
        content: new Model(type: Quote::class)
    )]
    #[OA\RequestBody(
        description: 'Quote data',
        content: new OA\JsonContent(
            properties: [
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
                new OA\Property(property: 'weight', type: 'float'),
                new OA\Property(property: 'totalTaxIncluded', type: 'float'),
                new OA\Property(property: 'quoteSentToCustomer', type: 'boolean'),
                new OA\Property(property: 'qualificationMessage', type: 'string'),
                new OA\Property(property: 'conversionId', type: 'integer'),
                new OA\Property(property: 'transportConditionId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        $contremarqueId,
        #[MapRequestPayload] CreateQuoteRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        if (empty($requestDTO->transformedIntoAnOrder)) {
            $requestDTO->transformedIntoAnOrder = false;
        }
        if (empty($requestDTO->archived)) {
            $requestDTO->archived = false;
        }
        $createQuoteCommand = new CreateQuoteCommand($contremarqueId, $requestDTO);

        $quoteResponse = $this->handle($createQuoteCommand);

        return SuccessResponse::create(
            'quote_creation',
            $quoteResponse->toArray(),
            'Quote creation is successful'
        );
    }
}
