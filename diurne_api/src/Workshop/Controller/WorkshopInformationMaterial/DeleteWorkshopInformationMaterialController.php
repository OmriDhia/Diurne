<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformationMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteWorkshopInformationMaterial\DeleteWorkshopInformationMaterialCommand;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteWorkshopInformationMaterialController extends CommandQueryController
{
    #[Route('/api/workshop-information-materials/{id}', name: 'workshop_information_material_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information material deleted',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: new Model(type: WorkshopInformationMaterial::class))])
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to delete workshop information material'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $response = $this->handle(new DeleteWorkshopInformationMaterialCommand($id));

        return SuccessResponse::create(
            'workshop_information_material_deleted',
            $response->id,
            'Workshop information material deleted successfully.'
        );
    }
}
