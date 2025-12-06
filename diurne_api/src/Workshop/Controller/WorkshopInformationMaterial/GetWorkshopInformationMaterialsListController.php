<?php

declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopInformationMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopInformationMaterial\GetWorkshopInformationMaterialQuery;
use App\Workshop\Bus\Query\GetWorkshopInformationMaterialById\GetWorkshopInformationMaterialByIdQuery;
use App\Workshop\Entity\WorkshopInformationMaterial;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopInformationMaterialsListController extends CommandQueryController
{
    #[Route('/api/workshop-information-materials', name: 'workshop_information_materials_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: WorkshopInformationMaterial::class)))])
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        $response = $this->ask(new GetWorkshopInformationMaterialQuery());

        return SuccessResponse::create(
            'workshop_information_material_list',
            $response->toArray(),
            'List of workshop information materials.'
        );
    }

    #[Route('/api/workshop-information-materials/{id}', name: 'workshop_information_material_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop information material',
        content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: new Model(type: WorkshopInformationMaterial::class))])
    )]
    #[OA\Tag(name: 'Workshop')]
    public function getById(int $id): JsonResponse
    {
        $response = $this->ask(new GetWorkshopInformationMaterialByIdQuery($id));

        return SuccessResponse::create(
            'workshop_information_material_by_id',
            ['data' => $response->workshopInformationMaterial->toArray()],
            'Workshop information material fetched successfully.'
        );
    }
}
