<?php

namespace App\Contremarque\Controller\ImageCommandDesignerAssignment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\ImageCommandDesignerAssignment\GetImageCommandDesignerAssignmentQuery;
use App\Contremarque\Entity\ImageCommand\ImageCommandDesignerAssignment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-command/assign-designer', name: 'get_image_command_designer_assignment', methods: ['GET'])]
class GetImageCommandDesignerAssignmentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all available image commands designer assignment',
        content: new Model(type: ImageCommandDesignerAssignment::class)
    )]
    #[OA\RequestBody(
        description: 'Fetch all image commands designer assignment',
        content: new OA\JsonContent()
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetImageCommandDesignerAssignmentQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_image_commands_designer_assignment',
            $response->toArray(),
            'get_image_command_designer_assignment'

        );
    }
}
