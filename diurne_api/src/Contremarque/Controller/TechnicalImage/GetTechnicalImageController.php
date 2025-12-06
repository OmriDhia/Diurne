<?php

namespace App\Contremarque\Controller\TechnicalImage;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\TechnicalImage\GetTechnicalImageQuery;
use App\Contremarque\Entity\ImageCommand\TechnicalImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/technical-image', name: 'get_technical_image', methods: ['GET'])]
class GetTechnicalImageController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available Technical image ',
        content: new Model(type: TechnicalImage::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all image commands',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetTechnicalImageQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_technical_image',
            $response->toArray()

        );
    }
}
