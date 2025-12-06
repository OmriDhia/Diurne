<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateQuoteCarpetSpecification\UpdateQuoteCarpetSpecificationCommand;
use App\Contremarque\Bus\Command\UpdateQuoteDetail\UpdateQuoteDetailCommand;
use App\Contremarque\DTO\UpdateQuoteDetailWithCarpetSpecificationRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateQuoteDetailController extends CommandQueryController
{
    #[Route(
        '/api/Quote/{quoteId}/updateQuoteDetail/{quoteDetailId}',
        name: 'quote_detail_update',
        methods: ['PUT']
    )]
    #[OA\Put(
        path: '/api/Quote/{quoteId}/updateQuoteDetail/{quoteDetailId}',
        tags: ['Devis'],
        summary: 'Updates a Quote Detail and its associated Carpet Specification',
        requestBody: new OA\RequestBody(
            description: 'Updated data for Quote Detail and Carpet Specification',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: new Model(type: UpdateQuoteDetailWithCarpetSpecificationRequestDto::class))
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Quote Detail and Carpet Specification updated',
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
        int $quoteId,
        int $quoteDetailId,
        #[MapRequestPayload] UpdateQuoteDetailWithCarpetSpecificationRequestDto $payload
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Update QuoteDetail
        $updateQuoteDetailCommand = new UpdateQuoteDetailCommand(
            $quoteId,
            $quoteDetailId,
            $payload->quoteDetail
        );
        $quoteDetailResponse = $this->handle($updateQuoteDetailCommand);

        // Update CarpetSpecification
        $updateCarpetSpecificationCommand = new UpdateQuoteCarpetSpecificationCommand(
            $quoteDetailId,
            $payload->carpetSpecification
        );
        $carpetSpecificationResponse = $this->handle($updateCarpetSpecificationCommand);

        return SuccessResponse::create('quote_detail_update', [
            'quoteDetail' => $quoteDetailResponse->toArray(),
            'carpetSpecification' => $carpetSpecificationResponse->toArray(),
        ]);
    }
}
