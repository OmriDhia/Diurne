<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialShape\UpdateSpecialShapeCommand;
use App\Setting\DTO\UpdateSpecialShapeRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateSpecialShapeController extends CommandQueryController
{
    #[Route('/api/specialShape/{id}', name: 'specialShape_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'SpecialShape updated successfully.',
        content: new OA\JsonContent(
            ref: new Model(type: UpdateSpecialShapeRequestDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'label', type: 'string'),
            ]
        )
    )]
    #[Security(name: null)]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateSpecialShapeRequestDto $updateDto
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        // Handle the update event command
        $updateCommand = new UpdateSpecialShapeCommand(
            $id,
            $updateDto->label,
        );

        $response = $this->handle($updateCommand);

        // $data = $response->toArray();

        return SuccessResponse::create(
            'specialShape_update',
            $response->toArray(),
            "SpecialShape updated successfully"
        );
    }
}
