<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateDesignerComposition\CreateDesignerCompositionCommand;
use App\Contremarque\DTO\CreateDesignerCompositionRequestDto;
use App\Contremarque\Entity\DesignerComposition;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateDesignerCompositionController extends CommandQueryController
{
    #[Route('/api/createDesignerComposition', name: 'designer_composition_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Designer Composition creation',
        content: new Model(type: DesignerComposition::class)
    )]
    #[OA\RequestBody(
        description: 'Designer Composition data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'materialId', type: 'integer'),
                new OA\Property(property: 'carpetSpecificationId', type: 'integer'),
                new OA\Property(property: 'rate', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateDesignerCompositionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createDesignerCompositionCommand = new CreateDesignerCompositionCommand(
            $requestDTO->materialId,
            $requestDTO->carpetSpecificationId,
            $requestDTO->rate
        );

        $designerCompositionResponse = $this->handle($createDesignerCompositionCommand);

        return SuccessResponse::create(
            'designer_composition_creation',
            $designerCompositionResponse->toArray(),
            'Designer Composition creation'
        );
    }
}
