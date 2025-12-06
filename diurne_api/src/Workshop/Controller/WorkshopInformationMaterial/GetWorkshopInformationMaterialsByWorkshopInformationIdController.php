<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformationMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopInformationMaterialByWorkshopInformationId\GetWorkshopInformationMaterialByWorkshopInformationIdQuery;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopInformationMaterialsByWorkshopInformationIdController extends CommandQueryController
{
    #[Route('/api/workshop-information-materials/workshop-information/{workshopInformationId}', name: 'workshop_information_materials_by_workshop_information_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: WorkshopInformationMaterial::class)))])
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopInformationId): JsonResponse
    {
        $response = $this->ask(new GetWorkshopInformationMaterialByWorkshopInformationIdQuery($workshopInformationId));

        return SuccessResponse::create(
            'workshop_information_materials_by_workshop_information_id',
            $response->toArray(),
            'Workshop information materials fetched successfully.'
        );
    }
}
