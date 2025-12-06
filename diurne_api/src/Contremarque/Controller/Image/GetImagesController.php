<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\Image;

use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetImages\GetImagesQuery;
use App\Contremarque\DTO\Image\GetImagesQueryDTO;
use App\Contremarque\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetImagesController extends CommandQueryController
{
    public function __construct(
        private ValidatorInterface $validator,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $commandBus);
    }

    #[Route('/api/images', name: 'get_images', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retrieve all images with optional filters',
        content: new Model(type: Image::class)
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid query parameters',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'code', type: 'integer', example: 400),
            new OA\Property(property: 'message', type: 'string', example: 'Invalid query parameters'),
            new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string')),
        ])
    )]
    #[OA\Parameter(
        name: 'filter[id_contremarque]',
        description: 'Filter by Contremarque ID',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'filter[id_di]',
        description: 'Filter by ProjectDi ID',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'filter[id_location]',
        description: 'Filter by Location ID',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $filter = $request->query->all()['filter'] ?? [];

        $idContremarque = isset($filter['id_contremarque']) && is_numeric($filter['id_contremarque'])
            ? (int) $filter['id_contremarque']
            : null;
        $idDi = isset($filter['id_di']) && is_numeric($filter['id_di'])
            ? (int) $filter['id_di']
            : null;
        $idLocation = isset($filter['id_location']) && is_numeric($filter['id_location'])
            ? (int) $filter['id_location']
            : null;

        // Validate query parameters
        $constraints = new Assert\Collection([
            'id_contremarque' => [new Assert\Optional([new Assert\Positive()])],
            'id_di' => [new Assert\Optional([new Assert\Positive()])],
            'id_location' => [new Assert\Optional([new Assert\Positive()])],
        ]);

        $data = [
            'id_contremarque' => $idContremarque,
            'id_di' => $idDi,
            'id_location' => $idLocation,
        ];

        $violations = $this->validator->validate($data, $constraints);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return new JsonResponse([
                'code' => 400,
                'message' => 'Invalid query parameters',
                'errors' => $errors,
            ], 400);
        }

        $dto = new GetImagesQueryDTO(
            idContremarque: $idContremarque,
            idDi: $idDi,
            idLocation: $idLocation
        );

        $query = new GetImagesQuery($dto);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_images',
            $response->toArray()
        );
    }
}
