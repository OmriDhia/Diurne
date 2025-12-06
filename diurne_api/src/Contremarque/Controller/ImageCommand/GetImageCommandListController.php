<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\ImageCommand;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\DTO\ImageCommand\GetImageCommandListRequestDto;
use App\Contremarque\Bus\Query\ImageCommand\GetImageCommandQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Contremarque\Bus\Command\ImageCommand\ImageCommandResponse;

#[Route('/api/image-commands', name: 'get_image_command_list', methods: ['GET'])]
class GetImageCommandListController extends CommandQueryController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly QueryBus           $queryBus,
        private readonly CommandBus         $commandBus
    )
    {
        parent::__construct($queryBus, $commandBus);
    }

    #[OA\Response(
        response: 200,
        description: 'Fetch paginated list of image commands',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    ref: new Model(type: ImageCommandResponse::class)
                ),
                new OA\Property(
                    property: 'meta',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'page', type: 'integer', example: 1),
                        new OA\Property(property: 'limit', type: 'integer', example: 25),
                        new OA\Property(property: 'total', type: 'integer', example: 100),
                        new OA\Property(property: 'pages', type: 'integer', example: 4)
                    ]
                )
            ]
        )
    )]
    #[OA\Parameter(
        name: 'designerId',
        description: 'Filter by designer ID',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Page number',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Items per page',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 25, maximum: 100)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapQueryString] GetImageCommandListRequestDto $requestDto): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => JsonResponse::HTTP_UNAUTHORIZED,
                'message' => 'Unauthorized to access this content'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }


        $query = new GetImageCommandQuery(
            designerId: $requestDto->designerId,
            page: $requestDto->page,
            itemsPerPage: $requestDto->itemsPerPage,
            customer: $requestDto->customer,
            contremarque: $requestDto->contremarque,
            commercial: $requestDto->commercial,
            command: $requestDto->command,
            measurementName1: $requestDto->measurementName1,
            measurementName2: $requestDto->measurementName2,
            minDimensionValue1: $requestDto->minDimensionValue1,
            maxDimensionValue1: $requestDto->maxDimensionValue1,
            minDimensionValue2: $requestDto->minDimensionValue2,
            maxDimensionValue2: $requestDto->maxDimensionValue2,
            model: $requestDto->model,
            collection: $requestDto->collection,
            quality: $requestDto->quality,
            location: $requestDto->location,

        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_image_command_list',
            $response->toArray()
        );
    }
}
