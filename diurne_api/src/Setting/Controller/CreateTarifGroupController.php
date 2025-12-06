<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Exception\ValidationException;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TarifGroup\CreateTarifGroupCommand;
use App\Setting\DTO\CreateTarifGroupRequestDto;
use App\Setting\Entity\TarifGroup;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateTarifGroupController extends CommandQueryController
{
    #[Route('/api/createTarifGroup', name: 'tarifGroup_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'TarifGroup creation',
        content: new Model(type: TarifGroup::class)
    )]
    #[OA\RequestBody(
        description: 'TarifGroup data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'year', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateTarifGroupRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }


        $createTarifGroupCommand = new CreateTarifGroupCommand(
            $requestDTO->year
        );

        try {
            $tarifGroupResponse = $this->handle($createTarifGroupCommand);

            return SuccessResponse::create(
                'tarifGroup_creation',
                $tarifGroupResponse->toArray()
            );
        } catch (Exception $e) {
            return new JsonResponse(['code' => 500, 'message' => 'An error occurred: '.$e->getMessage()], 500);
        }
    }
}
