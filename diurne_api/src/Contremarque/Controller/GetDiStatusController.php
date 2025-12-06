<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetDiStatus\GetDiStatusQuery;
use App\Contremarque\Entity\DiStatus;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetDiStatusController extends CommandQueryController
{
    #[Route('/api/diStatuses', name: 'get_diStatuses', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get DiStatuses',
        content: new Model(type: DiStatus::class)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }
        $query = new GetDiStatusQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_diStatuses',
            $response
        );
    }
}
