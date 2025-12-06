<?php

namespace App\CheckingList\Controller\QualityRespect;

use App\CheckingList\Bus\Command\DeleteQualityRespect\DeleteQualityRespectCommand;
use App\CheckingList\Entity\QualityRespect;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteQualityRespectController extends CommandQueryController
{
    #[Route('/api/qualityRespects/{id}', name: 'quality_respect_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: QualityRespect::class))]
    #[OA\Parameter(name: 'id', description: 'Quality respect id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteQualityRespectCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create('quality_respect_deleted', $response->toArray(), 'Quality respect deleted', Response::HTTP_OK);
    }
}
