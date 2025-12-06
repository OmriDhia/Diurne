<?php

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Workshop\Bus\Query\GetWorkshopImage\GetWorkshopImageQuery;
use App\Workshop\Bus\Query\GetWorkshopImageById\GetWorkshopImageByIdQuery;
use App\Workshop\Entity\WorkshopImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;

class GetWorkshopImageController extends CommandQueryController
{
    #[Route('/api/workshopImages', name: 'workshop_image_list', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns List workshop image',
        content: new Model(type: WorkshopImage::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Workshop image not found'
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetWorkshopImageQuery();
        $workshopImage = $this->ask($query);
        if (empty($workshopImage->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'not found'],
                404
            );
        }
        return SuccessResponse::create(
            'workshop_information_retrieved',
            $workshopImage->toArray()
        );
    }
}