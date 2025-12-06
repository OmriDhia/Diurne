<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CloneQuoteDetail\CloneQuoteDetailCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CloneQuoteDetailController extends CommandQueryController
{
    #[Route(
        path: '/api/cloneQuoteDetail/{quoteDetailId}',
        name: 'clone_quote_detail',
        methods: ['POST']
    )]
    #[OA\Post(
        path: '/api/cloneQuoteDetail/{quoteDetailId}',
        tags: ['Contremarque'],
        summary: 'Clone a Quote Detail',
        responses: [
            new OA\Response(response: 200, description: 'Quote Detail cloned')
        ]
    )]
    public function __invoke(int $quoteDetailId): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CloneQuoteDetailCommand($quoteDetailId);
        $response = $this->handle($command);

        return SuccessResponse::create('quote_detail_cloned', $response->toArray());
    }
}
