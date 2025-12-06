<?php

// src/Contremarque/Controller/GetQuoteListController.php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Query\GetQuoteList\GetQuoteListQuery;
use App\Contremarque\DTO\GetQuoteListRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class GetQuoteListController extends CommandQueryController
{
    #[Route('/api/quotes', name: 'quote_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Quote listing',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Quote')
        )
    )]
    #[OA\Parameter(
        name: 'customer',
        in: 'query',
        description: 'Filter by customer',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'commercial',
        in: 'query',
        description: 'Filter by commercial',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'creationDate',
        in: 'query',
        description: 'Filter by creation date',
        required: false,
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Page number',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Items per page',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'order',
        in: 'query',
        description: 'Order by field',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'orderWay',
        in: 'query',
        description: 'Order way (ASC or DESC)',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'])
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        #[MapQueryString] GetQuoteListRequestDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('read', 'quote')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        // Optionnel : récupérer l'utilisateur actuel depuis la session si nécessaire
        $session = $this->container->get('request_stack')->getSession();
        $session->start();
        $currentUser = $session->get('user');

        // Création de la requête GetQuoteListQuery avec les paramètres du DTO
        $query = new GetQuoteListQuery(
            (int)$dto->page,
            (int)$dto->itemsPerPage,
            $dto->orderBy,
            $dto->orderWay,
            $dto->devis,
            $dto->contremarque,
            $dto->contremarqueId,
            $dto->locationId,
            $dto->customer,
            $dto->commercial,
            $dto->creationDate,
            $dto->validationDate,
            $currentUser->getId()
        );

        $response = $this->ask($query);

        // Retourner la réponse sous forme de JSON
        return new JsonResponse($response->toArray(), 200);
    }
}
