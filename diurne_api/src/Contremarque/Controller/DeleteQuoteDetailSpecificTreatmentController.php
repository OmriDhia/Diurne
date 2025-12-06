<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteQuoteDetailSpecificTreatment\DeleteQuoteDetailSpecificTreatmentCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteQuoteDetailSpecificTreatmentController extends CommandQueryController
{
    #[Route('/api/quoteDetailSpecificTreatment/{specificTreatmentId}', name: 'delete_QuoteDetailSpecificTreatment_by_id', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'QuoteDetailSpecificTreatment deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $specificTreatmentId
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteQuoteDetailSpecificTreatment = new DeleteQuoteDetailSpecificTreatmentCommand($specificTreatmentId);
        $this->handle($deleteQuoteDetailSpecificTreatment);

        return SuccessResponse::create(
            'delete_QuoteDetailSpecificTreatment_by_id',
            [],
            'QuoteDetailSpecificTreatment deleted successfully'
        );
    }
}
