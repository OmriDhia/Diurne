<?php

namespace App\CheckingList\Controller\QualityCheck;

use App\CheckingList\Bus\Command\DeleteQualityCheck\DeleteQualityCheckCommand;
use App\CheckingList\Entity\QualityCheck;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteQualityCheckController extends CommandQueryController
{
    #[Route('/api/qualityChecks/{id}', name: 'quality_check_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: QualityCheck::class))]
    #[OA\Parameter(name: 'id', description: 'Quality check id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteQualityCheckCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create('quality_check_deleted', $response->toArray(), 'Quality check deleted', Response::HTTP_OK);
    }
}
