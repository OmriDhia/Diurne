<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Exception\ValidationException;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialShape\CreateSpecialShapeCommand;
use App\Setting\DTO\CreateSpecialShapeRequestDto;
use App\Setting\Entity\SpecialShape;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateSpecialShapeController extends CommandQueryController
{
    #[Route('/api/createSpecialShape', name: 'specialShape_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'SpecialShape creation',
        content: new Model(type: SpecialShape::class)
    )]
    #[OA\RequestBody(
        description: 'SpecialShape data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'label', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateSpecialShapeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createSpecialShapeCommand = new CreateSpecialShapeCommand(
            $requestDTO->label
        );

        try {
            $specialShapeResponse = $this->handle($createSpecialShapeCommand);

            return SuccessResponse::create(
                'specialShape_creation',
                $specialShapeResponse->toArray()
            );
        } catch (ValidationException $e) {
            return new JsonResponse(['code' => 400, 'message' => $e->getErrors()], 400);
        } catch (Exception $e) {
            return new JsonResponse(['code' => 500, 'message' => 'An error occurred: '.$e->getMessage()], 500);
        }
    }
}
