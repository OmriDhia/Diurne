<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DesignerComposition\UpdateDesignerCompositionCommand;
use App\Contremarque\DTO\UpdateDesignerCompositionRequestDto;
use App\Contremarque\Entity\DesignerComposition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/designer-composition/{id}/update', name: 'designer_composition_update', methods: ['PUT'])]
class UpdateDesignerCompositionController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Designer Composition updated successfully',
        content: new Model(type: DesignerComposition::class)
    )]
    #[OA\RequestBody(
        description: 'Designer Composition data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'materialId', type: 'int', nullable: true),
                new OA\Property(property: 'rate', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateDesignerCompositionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateDesignerCompositionCommand(
            $id,
            $requestDTO->materialId,
            $requestDTO->rate
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'designer_composition_update',
            $response->toArray(),
            'Designer Composition updated successfully'
        );
    }
}
