<?php

namespace App\Contremarque\Controller\ImageCommand;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetImageCommandById\GetImageCommandByIdQuery;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-command/{id}', name: 'get_image_command_by_id', methods: ['GET'])]
class GetImageCommandByIdController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Get image command by id',
        content: new Model(type: ImageCommand::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch  image command',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int     $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }


        $query = new GetImageCommandByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_image_command_by_id',
            $response->toArray()

        );
    }
}
