<?php

namespace App\CheckingList\Controller\QualityCheck;

use App\CheckingList\Bus\Command\UpdateQualityCheck\UpdateQualityCheckCommand;
use App\CheckingList\DTO\QualityCheck\UpdateQualityCheckRequestDto;
use App\CheckingList\Entity\QualityCheck;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateQualityCheckController extends CommandQueryController
{
    #[Route('/api/qualityChecks/{id}', name: 'quality_check_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: QualityCheck::class))]
    #[OA\RequestBody(
        description: 'Quality check update data',
        content: new OA\JsonContent(
            properties: [
                // Graphic fields
                new OA\Property(property: 'graphic_relevant', type: 'boolean'),
                new OA\Property(property: 'graphic_validation', type: 'boolean'),
                new OA\Property(property: 'graphic_seen', type: 'boolean'),
                new OA\Property(property: 'graphic_comment', type: 'string'),
                
                // Instruction fields
                new OA\Property(property: 'instruction_relevant', type: 'boolean'),
                new OA\Property(property: 'instruction_compliance_validation', type: 'boolean'),
                new OA\Property(property: 'instruction_seen', type: 'boolean'),
                new OA\Property(property: 'instruction_comment', type: 'string'),
                
                // Repair fields
                new OA\Property(property: 'repair_relevant', type: 'boolean'),
                new OA\Property(property: 'repair_relevant_validation', type: 'boolean'),
                new OA\Property(property: 'repair_seen', type: 'boolean'),
                new OA\Property(property: 'repair_comment', type: 'string'),
                
                // Tightness fields
                new OA\Property(property: 'tightness_relevant', type: 'boolean'),
                new OA\Property(property: 'tightness_validation', type: 'boolean'),
                new OA\Property(property: 'tightness_seen', type: 'boolean'),
                new OA\Property(property: 'tightness_comment', type: 'string'),
                
                // Wool fields
                new OA\Property(property: 'wool_relevant', type: 'boolean'),
                new OA\Property(property: 'wool_quality_validation', type: 'boolean'),
                new OA\Property(property: 'wool_seen', type: 'boolean'),
                new OA\Property(property: 'wool_comment', type: 'string'),
                
                // Silk fields
                new OA\Property(property: 'silk_relevant', type: 'boolean'),
                new OA\Property(property: 'silk_quality_validation', type: 'boolean'),
                new OA\Property(property: 'silk_seen', type: 'boolean'),
                new OA\Property(property: 'silk_comment', type: 'string'),
                
                // Special Shape fields
                new OA\Property(property: 'special_shape_relevant', type: 'boolean'),
                new OA\Property(property: 'special_shape_relevant_validation', type: 'boolean'),
                new OA\Property(property: 'special_shape_seen', type: 'boolean'),
                new OA\Property(property: 'special_shape_comment', type: 'string'),
                
                // Corps Ondu Coins fields
                new OA\Property(property: 'corps_ondu_coins_relevant', type: 'boolean'),
                new OA\Property(property: 'corps_ondu_coins_validation', type: 'boolean'),
                new OA\Property(property: 'corps_ondu_coins_seen', type: 'boolean'),
                new OA\Property(property: 'corps_ondu_coins_comment', type: 'string'),
                
                // Velour Author fields
                new OA\Property(property: 'velour_author_relevant', type: 'boolean'),
                new OA\Property(property: 'velour_author_validation', type: 'boolean'),
                new OA\Property(property: 'velour_author_seen', type: 'boolean'),
                new OA\Property(property: 'velour_comment', type: 'string'),
                
                // Washing fields
                new OA\Property(property: 'washing_relevant', type: 'boolean'),
                new OA\Property(property: 'washing_validation', type: 'boolean'),
                new OA\Property(property: 'washing_seen', type: 'boolean'),
                new OA\Property(property: 'waching_comment', type: 'string'),
                
                // Cleaning fields
                new OA\Property(property: 'cleaning_relevant', type: 'boolean'),
                new OA\Property(property: 'cleaning_validation', type: 'boolean'),
                new OA\Property(property: 'cleaning_seen', type: 'boolean'),
                new OA\Property(property: 'cleaning_comment', type: 'string'),
                
                // Carving fields
                new OA\Property(property: 'carving_relevant', type: 'boolean'),
                new OA\Property(property: 'carving_validation', type: 'boolean'),
                new OA\Property(property: 'carving_seen', type: 'boolean'),
                new OA\Property(property: 'carving_comment', type: 'string'),
                
                // Fabric Color fields
                new OA\Property(property: 'fabric_color_relevant', type: 'boolean'),
                new OA\Property(property: 'fabric_color_validation', type: 'boolean'),
                new OA\Property(property: 'fabric_color_seen', type: 'boolean'),
                new OA\Property(property: 'fabric_color_comment', type: 'string'),
                
                // Frange fields
                new OA\Property(property: 'frange_relevant', type: 'boolean'),
                new OA\Property(property: 'frange_validation', type: 'boolean'),
                new OA\Property(property: 'frange_seen', type: 'boolean'),
                new OA\Property(property: 'frang_comment', type: 'string'),
                
                // No Binding fields
                new OA\Property(property: 'no_binding_relevant', type: 'boolean'),
                new OA\Property(property: 'no_binding_validation', type: 'boolean'),
                new OA\Property(property: 'no_binding_seen', type: 'boolean'),
                new OA\Property(property: 'no_binding_comment', type: 'string'),
                
                // Signature fields
                new OA\Property(property: 'signature_relevant', type: 'boolean'),
                new OA\Property(property: 'signature_validation', type: 'boolean'),
                new OA\Property(property: 'signature_seen', type: 'boolean'),
                new OA\Property(property: 'signature_comment', type: 'string'),
                
                // Without Backing fields
                new OA\Property(property: 'without_backing_relevant', type: 'boolean'),
                new OA\Property(property: 'without_backing_validation', type: 'boolean'),
                new OA\Property(property: 'without_backing_seen', type: 'boolean'),
                new OA\Property(property: 'without_backing_comment', type: 'string'),
                
                new OA\Property(property: 'comment', type: 'string')
            ]
        )
    )]
    #[OA\Parameter(name: 'id', description: 'Quality check id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateQualityCheckRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateQualityCheckCommand(
            id: $id,
            
            // Graphic fields
            graphicRelevant: $dto->graphic_relevant,
            graphicValidation: $dto->graphic_validation,
            graphicSeen: $dto->graphic_seen,
            graphicComment: $dto->graphic_comment,
            
            // Instruction fields
            instructionRelevant: $dto->instruction_relevant,
            instructionComplianceValidation: $dto->instruction_compliance_validation,
            instructionSeen: $dto->instruction_seen,
            instructionComment: $dto->instruction_comment,
            
            // Repair fields
            repairRelevant: $dto->repair_relevant,
            repairRelevantValidation: $dto->repair_relevant_validation,
            repairSeen: $dto->repair_seen,
            repairComment: $dto->repair_comment,
            
            // Tightness fields
            tightnessRelevant: $dto->tightness_relevant,
            tightnessValidation: $dto->tightness_validation,
            tightnessSeen: $dto->tightness_seen,
            tightnessComment: $dto->tightness_comment,
            
            // Wool fields
            woolRelevant: $dto->wool_relevant,
            woolQualityValidation: $dto->wool_quality_validation,
            woolSeen: $dto->wool_seen,
            woolComment: $dto->wool_comment,
            
            // Silk fields
            silkRelevant: $dto->silk_relevant,
            silkQualityValidation: $dto->silk_quality_validation,
            silkSeen: $dto->silk_seen,
            silkComment: $dto->silk_comment,
            
            // Special Shape fields
            specialShapeRelevant: $dto->special_shape_relevant,
            specialShapeRelevantValidation: $dto->special_shape_relevant_validation,
            specialShapeSeen: $dto->special_shape_seen,
            specialShapeComment: $dto->special_shape_comment,
            
            // Corps Ondu Coins fields
            corpsOnduCoinsRelevant: $dto->corps_ondu_coins_relevant,
            corpsOnduCoinsValidation: $dto->corps_ondu_coins_validation,
            corpsOnduCoinsSeen: $dto->corps_ondu_coins_seen,
            corpsOnduCoinsComment: $dto->corps_ondu_coins_comment,
            
            // Velour Author fields
            velourAuthorRelevant: $dto->velour_author_relevant,
            velourAuthorValidation: $dto->velour_author_validation,
            velourAuthorSeen: $dto->velour_author_seen,
            velourComment: $dto->velour_comment,
            
            // Washing fields
            washingRelevant: $dto->washing_relevant,
            washingValidation: $dto->washing_validation,
            washingSeen: $dto->washing_seen,
            wachingComment: $dto->waching_comment,
            
            // Cleaning fields
            cleaningRelevant: $dto->cleaning_relevant,
            cleaningValidation: $dto->cleaning_validation,
            cleaningSeen: $dto->cleaning_seen,
            cleaningComment: $dto->cleaning_comment,
            
            // Carving fields
            carvingRelevant: $dto->carving_relevant,
            carvingValidation: $dto->carving_validation,
            carvingSeen: $dto->carving_seen,
            carvingComment: $dto->carving_comment,
            
            // Fabric Color fields
            fabricColorRelevant: $dto->fabric_color_relevant,
            fabricColorValidation: $dto->fabric_color_validation,
            fabricColorSeen: $dto->fabric_color_seen,
            fabricColorComment: $dto->fabric_color_comment,
            
            // Frange fields
            frangeRelevant: $dto->frange_relevant,
            frangeValidation: $dto->frange_validation,
            frangeSeen: $dto->frange_seen,
            frangComment: $dto->frang_comment,
            
            // No Binding fields
            noBindingRelevant: $dto->no_binding_relevant,
            noBindingValidation: $dto->no_binding_validation,
            noBindingSeen: $dto->no_binding_seen,
            noBindingComment: $dto->no_binding_comment,
            
            // Signature fields
            signatureRelevant: $dto->signature_relevant,
            signatureValidation: $dto->signature_validation,
            signatureSeen: $dto->signature_seen,
            signatureComment: $dto->signature_comment,
            
            // Without Backing fields
            withoutBackingRelevant: $dto->without_backing_relevant,
            withoutBackingValidation: $dto->without_backing_validation,
            withoutBackingSeen: $dto->without_backing_seen,
            withoutBackingComment: $dto->without_backing_comment,
            
            comment: $dto->comment
        );

        $response = $this->handle($command);

        return SuccessResponse::create('quality_check_updated', $response->toArray(), 'Quality check updated');
    }
}
