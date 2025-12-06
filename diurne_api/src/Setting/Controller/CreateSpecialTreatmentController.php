<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialTreatment\CreateSpecialTreatmentCommand;
use App\Setting\DTO\CreateSpecialTreatmentRequestDto;
use App\Setting\Entity\SpecialTreatment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/specialTreatment/create', name: 'specialTreatment_creation', methods: ['POST'])]
class CreateSpecialTreatmentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'SpecialTreatment creation',
        content: new Model(type: SpecialTreatment::class)
    )]
    #[OA\RequestBody(
        description: 'SpecialTreatment data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'label', type: 'string'),
                new OA\Property(property: 'price', type: 'number'),
                new OA\Property(property: 'unit', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateSpecialTreatmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateSpecialTreatmentCommand(
            $requestDTO->label,
            $requestDTO->price,
            $requestDTO->unit,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'specialTreatment_creation',
            $response->toArray()
        );
    }
}
