<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\AttachCarpetDesignOrder\AttachCarpetDesignOrderCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AttachCarpetDesignOrderController extends CommandQueryController
{
    #[Route(
        '/api/quote-details/attach-carpet-design-order',
        name: 'attach_carpet_design_order',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Attach CarpetDesignOrder to QuoteDetail',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'quoteDetailId', type: 'integer'),
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer'),
            ]
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'quoteDetailId', type: 'integer', description: 'The ID of the quote detail to attach the carpet design order to'),
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer', description: 'The ID of the carpet design order to attach')
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(Request $request): JsonResponse
    {
        // Extract the ids from the request body
        $data = json_decode($request->getContent(), true);

        if (empty($data['quoteDetailId']) || empty($data['carpetDesignOrderId'])) {
            return new JsonResponse(['error' => 'Both quoteDetailId and carpetDesignOrderId are required.'], 400);
        }

        $command = new AttachCarpetDesignOrderCommand($data['carpetDesignOrderId'], $data['quoteDetailId']);
        $response = $this->handle($command);

        return SuccessResponse::create('attach_carpet_design_order', $response->toArray());
    }
}
