<?php

namespace App\CheckingList\Controller\QualityRespect;

use App\CheckingList\Bus\Command\UpdateQualityRespect\UpdateQualityRespectCommand;
use App\CheckingList\DTO\QualityRespect\UpdateQualityRespectRequestDto;
use App\CheckingList\Entity\QualityRespect;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateQualityRespectController extends CommandQueryController
{
    #[Route('/api/qualityRespects/{id}', name: 'quality_respect_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: QualityRespect::class))]
    #[OA\RequestBody(
        description: 'Quality respect update data',
        content: new OA\JsonContent(
            properties: [
                // Respect Plan
                new OA\Property(property: 'respect_plan_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_plan_valid', type: 'boolean'),
                new OA\Property(property: 'respect_plan_seen', type: 'boolean'),
                new OA\Property(property: 'respect_plan_comment', type: 'string'),
                
                // Respect Door Height
                new OA\Property(property: 'respect_door_height_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_door_height_valid', type: 'boolean'),
                new OA\Property(property: 'respect_door_height_seen', type: 'boolean'),
                new OA\Property(property: 'respect_door_height_comment', type: 'string'),
                
                // Respect Max Min Length
                new OA\Property(property: 'respect_max_min_length_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_max_min_length_valid', type: 'boolean'),
                new OA\Property(property: 'respect_max_min_length_seen', type: 'boolean'),
                
                // Respect Max Min Width
                new OA\Property(property: 'respect_max_min_width_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_max_min_width_valid', type: 'boolean'),
                new OA\Property(property: 'respect_max_min_width_seen', type: 'boolean'),
                
                // Wall Distance Right
                new OA\Property(property: 'respectwall_distance_right_relevant', type: 'boolean'),
                new OA\Property(property: 'respectwall_distance_right_valid', type: 'boolean'),
                new OA\Property(property: 'respectwall_distance_right_seen', type: 'boolean'),
                
                // Wall Distance Left
                new OA\Property(property: 'respectwall_distance_left_relevant', type: 'boolean'),
                new OA\Property(property: 'respectwall_distance_left_valid', type: 'boolean'),
                new OA\Property(property: 'respectwall_distance_left_seen', type: 'boolean'),
                
                // Respect Foss
                new OA\Property(property: 'respect_foss_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_foss_valide', type: 'boolean'),
                new OA\Property(property: 'respect_foss_seen', type: 'boolean'),
                new OA\Property(property: 'respect_foss_comment', type: 'string'),
                
                // Respect Other Carpet
                new OA\Property(property: 'respect_other_carpet_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_other_carpet_valid', type: 'boolean'),
                new OA\Property(property: 'respect_other_carpet_seen', type: 'boolean'),
                new OA\Property(property: 'respect_other_carpet_comment', type: 'string'),
                
                // Respect Color
                new OA\Property(property: 'respect_color_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_color_valid', type: 'boolean'),
                new OA\Property(property: 'respect_color_seen', type: 'boolean'),
                new OA\Property(property: 'respect_color_comment', type: 'string'),
                
                // Respect Material
                new OA\Property(property: 'respect_material_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_material_valid', type: 'boolean'),
                new OA\Property(property: 'respect_material_seen', type: 'boolean'),
                new OA\Property(property: 'respect_material_comment', type: 'string'),
                
                // Respect Remark
                new OA\Property(property: 'respect_remark_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_remark_valid', type: 'boolean'),
                new OA\Property(property: 'respect_remark_seen', type: 'boolean'),
                new OA\Property(property: 'respect_remark_comment', type: 'string'),
                
                // Respect Velour
                new OA\Property(property: 'respect_velour_relevant', type: 'boolean'),
                new OA\Property(property: 'respect_velour_valid', type: 'boolean'),
                new OA\Property(property: 'respect_velour_seen', type: 'boolean'),
                new OA\Property(property: 'respect_velour_comment', type: 'string'),
                
                // Wall Distance Top
                new OA\Property(property: 'wall_distance_top_relevant', type: 'boolean'),
                new OA\Property(property: 'wall_distance_top_valid', type: 'boolean'),
                new OA\Property(property: 'wall_distance_top_seen', type: 'boolean'),
                
                // Wall Distance Bottom
                new OA\Property(property: 'wall_distance_bottom_relevant', type: 'boolean'),
                new OA\Property(property: 'wall_distance_bottom_valid', type: 'boolean'),
                new OA\Property(property: 'wall_distance_bottom_seen', type: 'boolean'),
                
                // Other fields
                new OA\Property(property: 'penalty', type: 'string'),
                new OA\Property(property: 'order_status', type: 'boolean')
            ]
        )
    )]
    #[OA\Parameter(name: 'id', description: 'Quality respect id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateQualityRespectRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateQualityRespectCommand(
            id: $id,
            
            // Respect Plan
            respectPlanRelevant: $dto->respect_plan_relevant,
            respectPlanValid: $dto->respect_plan_valid,
            respectPlanSeen: $dto->respect_plan_seen,
            respectPlanComment: $dto->respect_plan_comment,
            
            // Respect Door Height
            respectDoorHeightRelevant: $dto->respect_door_height_relevant,
            respectDoorHeightValid: $dto->respect_door_height_valid,
            respectDoorHeightSeen: $dto->respect_door_height_seen,
            respectDoorHeightComment: $dto->respect_door_height_comment,
            
            // Respect Max Min Length
            respectMaxMinLengthRelevant: $dto->respect_max_min_length_relevant,
            respectMaxMinLengthValid: $dto->respect_max_min_length_valid,
            respectMaxMinLengthSeen: $dto->respect_max_min_length_seen,
            
            // Respect Max Min Width
            respectMaxMinWidthRelevant: $dto->respect_max_min_width_relevant,
            respectMaxMinWidthValid: $dto->respect_max_min_width_valid,
            respectMaxMinWidthSeen: $dto->respect_max_min_width_seen,
            
            // Wall Distance Right
            respectwallDistanceRightRelevant: $dto->respectwall_distance_right_relevant,
            respectwallDistanceRightValid: $dto->respectwall_distance_right_valid,
            respectwallDistanceRightSeen: $dto->respectwall_distance_right_seen,
            
            // Wall Distance Left
            respectwallDistanceLeftRelevant: $dto->respectwall_distance_left_relevant,
            respectwallDistanceLeftValid: $dto->respectwall_distance_left_valid,
            respectwallDistanceLeftSeen: $dto->respectwall_distance_left_seen,
            
            // Respect Foss
            respectFossRelevant: $dto->respect_foss_relevant,
            respectFossValide: $dto->respect_foss_valide,
            respectFossSeen: $dto->respect_foss_seen,
            respectFossComment: $dto->respect_foss_comment,
            
            // Respect Other Carpet
            respectOtherCarpetRelevant: $dto->respect_other_carpet_relevant,
            respectOtherCarpetValid: $dto->respect_other_carpet_valid,
            respectOtherCarpetSeen: $dto->respect_other_carpet_seen,
            respectOtherCarpetComment: $dto->respect_other_carpet_comment,
            
            // Respect Color
            respectColorRelevant: $dto->respect_color_relevant,
            respectColorValid: $dto->respect_color_valid,
            respectColorSeen: $dto->respect_color_seen,
            respectColorComment: $dto->respect_color_comment,
            
            // Respect Material
            respectMaterialRelevant: $dto->respect_material_relevant,
            respectMaterialValid: $dto->respect_material_valid,
            respectMaterialSeen: $dto->respect_material_seen,
            respectMaterialComment: $dto->respect_material_comment,
            
            // Respect Remark
            respectRemarkRelevant: $dto->respect_remark_relevant,
            respectRemarkValid: $dto->respect_remark_valid,
            respectRemarkSeen: $dto->respect_remark_seen,
            respectRemarkComment: $dto->respect_remark_comment,
            
            // Respect Velour
            respectVelourRelevant: $dto->respect_velour_relevant,
            respectVelourValid: $dto->respect_velour_valid,
            respectVelourSeen: $dto->respect_velour_seen,
            respectVelourComment: $dto->respect_velour_comment,
            
            // Wall Distance Top
            wallDistanceTopRelevant: $dto->wall_distance_top_relevant,
            wallDistanceTopValid: $dto->wall_distance_top_valid,
            wallDistanceTopSeen: $dto->wall_distance_top_seen,
            
            // Wall Distance Bottom
            wallDistanceBottomRelevant: $dto->wall_distance_bottom_relevant,
            wallDistanceBottomValid: $dto->wall_distance_bottom_valid,
            wallDistanceBottomSeen: $dto->wall_distance_bottom_seen,
            
            // Other fields
            penalty: $dto->penalty,
            orderStatus: $dto->order_status
        );

        $response = $this->handle($command);

        return SuccessResponse::create('quality_respect_updated', $response->toArray(), 'Quality respect updated');
    }
}
