<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Command\ProjectDi\CreateProjectDiCommand;
use App\Contremarque\DTO\ProjectDiRequestDto;
use App\Contremarque\Entity\ProjectDi;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateProjectDiController extends CommandQueryController
{
    #[Route('/api/createProjectDi', name: 'project_di_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'ProjectDi creation',
        content: new Model(type: ProjectDi::class)
    )]
    #[OA\RequestBody(
        description: 'ProjectDi data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'format', type: 'string'),
                new OA\Property(property: 'deadline', type: 'datetime'),
                new OA\Property(property: 'transmitted_to_studio', type: 'boolean'),
                new OA\Property(property: 'transmition_date', type: 'datetime'),
                new OA\Property(property: 'unit_id', type: 'integer'),
                new OA\Property(property: 'contremarque_id', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] ProjectDiRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createProjectDiCommand = new CreateProjectDiCommand(
            $requestDTO->format,
            $requestDTO->deadline,
            $requestDTO->transmitted_to_studio,
            $requestDTO->transmition_date,
            $requestDTO->unit_id,
            $requestDTO->contremarque_id,
        );

        $projectDi = $this->handle($createProjectDiCommand);

        return new JsonResponse(['project_di' => $projectDi->toArray()], JsonResponse::HTTP_OK);
    }
}
