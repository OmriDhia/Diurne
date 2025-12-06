<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetDesignerAssignments\GetDesignerAssignmentsQuery;
use App\Contremarque\Entity\DesignerAssignment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetDesignerAssignmentsController extends CommandQueryController
{
    #[Route('/api/carpetDesignOrders/{carpetDesignOrderId}/designerAssignments', name: 'get_designer_assignments', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get Designer Assignments for a specific Carpet Design Order',
        content: new Model(type: DesignerAssignment::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $carpetDesignOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GetDesignerAssignmentsQuery($carpetDesignOrderId);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_designer_assignments',
            $response->toArray()
        );
    }
}
