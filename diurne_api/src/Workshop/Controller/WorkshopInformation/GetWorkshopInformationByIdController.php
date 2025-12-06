<?php

namespace App\Workshop\Controller\WorkshopInformation;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopInformationById\GetWorkshopInformationByIdQuery;
use App\Workshop\Entity\WorkshopInformation;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopInformationByIdController extends CommandQueryController
{
    #[Route('/api/workshopInformation/{id}', name: 'workshop_information_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns single workshop information',
        content: new Model(type: WorkshopInformation::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop information not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop information ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetWorkshopInformationByIdQuery($id);
        $workshopInformation = $this->ask($query);


        return SuccessResponse::create(
            'workshop_information_retrieved',
            $workshopInformation->toArray()
        );
    }
}