<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteQuoteDetail\DeleteQuoteDetailCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteQuoteDetailController extends CommandQueryController
{
    #[Route(
        '/api/Quote/{quoteId}/deleteQuoteDetail/{quoteDetailId}',
        name: 'quote_detail_deletion',
        methods: ['DELETE']
    )]
    #[OA\Response(
        response: 200,
        description: 'QuoteDetail deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $quoteId, int $quoteDetailId): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new DeleteQuoteDetailCommand($quoteId, $quoteDetailId);
        $this->handle($command);

        return SuccessResponse::create(
            'quote_detail_deletion',
            ['quoteDetailId' => $quoteDetailId],
            'QuoteDetail deleted successfully',
            SuccessResponse::HTTP_OK
        );
    }
}
