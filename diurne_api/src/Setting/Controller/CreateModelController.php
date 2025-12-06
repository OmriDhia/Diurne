<?php

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Model\CreateModelCommand;
use App\Setting\DTO\CreateModelRequestDto;
use App\Setting\Repository\ModelRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/api/models', name: 'create_model', methods: ['POST'])]
#[OA\Response(
    response: 200,
    description: 'Model creation',
    content: new Model(type: \App\Setting\Entity\Model::class)
)]
#[OA\RequestBody(
    description: 'Model data',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'string'),
            new OA\Property(property: 'number_max', type: 'integer'),
        ]
    )
)]
#[OA\Tag(name: 'Setting')]
class CreateModelController extends CommandQueryController
{
    private ModelRepository $modelRepository;

    #[Required]
    public function setModelRepository(ModelRepository $modelRepository): void
    {
        $this->modelRepository = $modelRepository;
    }

    public function __invoke(
        #[MapRequestPayload] CreateModelRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $existingModel = $this->modelRepository->findOneBy(['code' => $requestDTO->code]);
        if ($existingModel) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'A model with this code already exists.'
                ],
                409
            );
        }

        $createCommand = new CreateModelCommand(
            $requestDTO->code,
            $requestDTO->number_max
        );

        try {
            $response = $this->handle($createCommand);
            return SuccessResponse::create(
                'model_creation',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
