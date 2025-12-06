<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetSpecificTreatment\CreateCarpetSpecificTreatmentCommand;
use App\Contremarque\DTO\CreateCarpetSpecificTreatmentRequestDto;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/quote-detail/{quoteDetailId}/carpet-specific-treatment/create', name: 'carpetSpecificTreatment_create', methods: ['POST'])]
class CreateCarpetSpecificTreatmentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Carpet Specific Treatment creation',
        content: new Model(type: CarpetSpecificTreatment::class)
    )]
    #[OA\RequestBody(
        description: 'Carpet Specific Treatment data',
        content: new OA\JsonContent(
            required: ['treatment'],
            properties: [
                new OA\Property(property: 'treatmentId', type: 'int')
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        $quoteDetailId,
        #[MapRequestPayload] CreateCarpetSpecificTreatmentRequestDto $requestDto
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $createCommand = new CreateCarpetSpecificTreatmentCommand(
            (int) $quoteDetailId,
            (int) $requestDto->treatmentId

        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create('carpetSpecificTreatment_create', $response->toArray());
    }
}
