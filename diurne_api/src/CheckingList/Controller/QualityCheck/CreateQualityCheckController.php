<?php

namespace App\CheckingList\Controller\QualityCheck;

use App\CheckingList\Bus\Command\CreateQualityCheck\CreateQualityCheckCommand;
use App\CheckingList\DTO\QualityCheck\CreateQualityCheckRequestDto;
use App\CheckingList\Entity\QualityCheck;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateQualityCheckController extends CommandQueryController
{
    #[Route('/api/qualityChecks', name: 'quality_check_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: QualityCheck::class))]
    #[OA\RequestBody(
        description: 'Quality check data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'checking_list_id', type: 'integer', example: 1),
                new OA\Property(property: 'graphic_validation', type: 'boolean', example: true),
                new OA\Property(property: 'graphic_comment', type: 'string'),
                new OA\Property(property: 'instruction_compliance_validation', type: 'boolean'),
                new OA\Property(property: 'instruction_comment', type: 'string'),
                new OA\Property(property: 'comment', type: 'string')
            ]
        )
    )]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapRequestPayload] CreateQualityCheckRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateQualityCheckCommand(
            checkingListId: $dto->checking_list_id,
            graphicValidation: $dto->graphic_validation,
            graphicComment: $dto->graphic_comment,
            instructionComplianceValidation: $dto->instruction_compliance_validation,
            instructionComment: $dto->instruction_comment,
            repairRelevantValidation: $dto->repair_relevant_validation,
            repairComment: $dto->repair_comment,
            tightnessValidation: $dto->tightness_validation,
            tightnessComment: $dto->tightness_comment,
            woolQualityValidation: $dto->wool_quality_validation,
            woolComment: $dto->wool_comment,
            silkQualityValidation: $dto->silk_quality_validation,
            silkComment: $dto->silk_comment,
            specialShapeRelevantValidation: $dto->special_shape_relevant_validation,
            specialShapeComment: $dto->special_shape_comment,
            corpsOnduCoinsValidation: $dto->corps_ondu_coins_validation,
            corpsOnduCoinsComment: $dto->corps_ondu_coins_comment,
            velourAuthorValidation: $dto->velour_author_validation,
            velourComment: $dto->velour_comment,
            washingValidation: $dto->washing_validation,
            wachingComment: $dto->waching_comment,
            cleaningValidation: $dto->cleaning_validation,
            cleaningComment: $dto->cleaning_comment,
            carvingValidation: $dto->carving_validation,
            carvingComment: $dto->carving_comment,
            fabricColorValidation: $dto->fabric_color_validation,
            fabricColorComment: $dto->fabric_color_comment,
            frangeValidation: $dto->frange_validation,
            frangComment: $dto->frang_comment,
            noBindingValidation: $dto->no_binding_validation,
            noBindingComment: $dto->no_binding_comment,
            signatureValidation: $dto->signature_validation,
            signatureComment: $dto->signature_comment,
            withoutBackingValidation: $dto->without_backing_validation,
            withoutBackingComment: $dto->without_backing_comment,
            comment: $dto->comment
        );

        $response = $this->handle($command);

        return SuccessResponse::create('quality_check_created', $response->toArray(), 'Quality check created', Response::HTTP_CREATED);
    }
}
