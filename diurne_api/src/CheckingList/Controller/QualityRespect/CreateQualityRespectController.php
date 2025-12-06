<?php

namespace App\CheckingList\Controller\QualityRespect;

use App\CheckingList\Bus\Command\CreateQualityRespect\CreateQualityRespectCommand;
use App\CheckingList\DTO\QualityRespect\CreateQualityRespectRequestDto;
use App\CheckingList\Entity\QualityRespect;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateQualityRespectController extends CommandQueryController
{
    #[Route('/api/qualityRespects', name: 'quality_respect_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: QualityRespect::class))]
    #[OA\RequestBody(
        description: 'Quality respect data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'checking_list_id', type: 'integer', example: 1),
                new OA\Property(property: 'respect_plan_valid', type: 'boolean'),
                new OA\Property(property: 'respect_plan_comment', type: 'string'),
                new OA\Property(property: 'respect_door_height_valid', type: 'boolean'),
                new OA\Property(property: 'respect_door_height_comment', type: 'string'),
                new OA\Property(property: 'penalty', type: 'string'),
                new OA\Property(property: 'order_status', type: 'boolean')
            ]
        )
    )]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapRequestPayload] CreateQualityRespectRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new CreateQualityRespectCommand(
            checkingListId: $dto->checking_list_id,
            respectPlanValid: $dto->respect_plan_valid,
            respectPlanComment: $dto->respect_plan_comment,
            respectDoorHeightValid: $dto->respect_door_height_valid,
            respectDoorHeightComment: $dto->respect_door_height_comment,
            respectMaxMinLengthValid: $dto->respect_max_min_length_valid,
            respectMaxMinWidthValid: $dto->respect_max_min_width_valid,
            respectwallDistanceRightValid: $dto->respectwall_distance_right_valid,
            respectwallDistanceLeftValid: $dto->respectwall_distance_left_valid,
            penalty: $dto->penalty,
            respectFossValide: $dto->respect_foss_valide,
            respectFossComment: $dto->respect_foss_comment,
            respectOtherCarpetValid: $dto->respect_other_carpet_valid,
            respectOtherCarpetComment: $dto->respect_other_carpet_comment,
            respectColorValid: $dto->respect_color_valid,
            respectColorComment: $dto->respect_color_comment,
            respectMaterialValid: $dto->respect_material_valid,
            respectMaterialComment: $dto->respect_material_comment,
            respectRemarkValid: $dto->respect_remark_valid,
            respectRemarkComment: $dto->respect_remark_comment,
            respectVelourValid: $dto->respect_velour_valid,
            respectVelourComment: $dto->respect_velour_comment,
            wallDistanceTopValid: $dto->wall_distance_top_valid,
            wallDistanceBottomValid: $dto->wall_distance_bottom_valid,
            orderStatus: $dto->order_status
        );

        $response = $this->handle($command);

        return SuccessResponse::create('quality_respect_created', $response->toArray(), 'Quality respect created', Response::HTTP_CREATED);
    }
}
