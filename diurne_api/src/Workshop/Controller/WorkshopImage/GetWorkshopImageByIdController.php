<?php

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Workshop\Bus\Query\GetWorkshopImageById\GetWorkshopImageByIdQuery;
use App\Workshop\Entity\WorkshopImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;

class GetWorkshopImageByIdController extends CommandQueryController
{
    #[Route('/api/workshopImages/{id}', name: 'workshop_image_get_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns single workshop image',
        content: new Model(type: WorkshopImage::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop image not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop image ID',
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

        $query = new GetWorkshopImageByIdQuery($id);
        $workshopImage = $this->ask($query);

        return SuccessResponse::create(
            'workshop_information_retrieved',
            $workshopImage->toArray()
        );
    }
}