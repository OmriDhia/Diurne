<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetProjectDisByContremarque\GetProjectDisByContremarqueQuery;
use App\Contremarque\Bus\Query\GetProjectDisByContremarque\GetProjectDisByContremarqueResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetProjectDisByContremarqueController extends CommandQueryController
{
    #[Route('/api/contremarque/{id}/projectDis', name: 'get_project_dis_by_contremarque', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get projectDis by contremarque',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'projectDi', type: 'integer'),
                new OA\Property(property: 'demande_number', type: 'string'),
                new OA\Property(property: 'contremarque', type: 'integer'),
                new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                new OA\Property(property: 'format', type: 'string'),
                new OA\Property(property: 'deadline', type: 'string', format: 'date-time'),
                new OA\Property(property: 'transmitted_to_studio', type: 'boolean'),
                new OA\Property(property: 'transmition_date', type: 'string', format: 'date-time'),
                new OA\Property(property: 'attachments', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'unit', type: 'array', items: new OA\Items(type: 'object')),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GetProjectDisByContremarqueQuery($id);
        /** @var GetProjectDisByContremarqueResponse $response */
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_project_dis_by_contremarque',
            $response->toArray()
        );
    }
}
