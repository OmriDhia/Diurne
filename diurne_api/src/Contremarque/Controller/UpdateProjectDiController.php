<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Command\ProjectDi\UpdateProjectDiCommand;
use App\Contremarque\DTO\UpdateProjectDiDTO;
use App\Contremarque\Entity\ProjectDi;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateProjectDiController extends CommandQueryController
{
    #[Route('/api/projectDi/{id}/update', name: 'project_di_update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/projectDi/{id}/update',
        tags: ['Contremarque'],
        summary: 'Updates an existing ProjectDi',
        requestBody: new OA\RequestBody(
            description: 'ProjectDi update data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'format', type: 'string', nullable: true),
                    new OA\Property(property: 'deadline', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'transmitted_to_studio', type: 'boolean', nullable: true),
                    new OA\Property(property: 'transmition_date', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'unit_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'contremarque_id', type: 'integer', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'ProjectDi updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'project_di', type: 'object', ref: new Model(type: ProjectDi::class)),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 400, description: 'Bad Request'),
        ]
    )]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateProjectDiDTO $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        try {
            $updateProjectDiCommand = new UpdateProjectDiCommand(
                $id,
                $requestDTO->format,
                $requestDTO->deadline,
                $requestDTO->transmitted_to_studio,
                $requestDTO->transmition_date,
                $requestDTO->unit_id,
                $requestDTO->contremarque_id
            );

            $projectDi = $this->handle($updateProjectDiCommand);

            return new JsonResponse(['project_di' => $projectDi->toArray()], JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
