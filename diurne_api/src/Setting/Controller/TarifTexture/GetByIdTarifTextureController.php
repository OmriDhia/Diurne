<?php

declare(strict_types=1);

namespace App\Setting\Controller\TarifTexture;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\TarifTexture\GetByIdTarifTextureQuery;
use App\Setting\Entity\TarifTexture;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetByIdTarifTextureController extends CommandQueryController
{
    #[Route('/api/tarifTexture/{id}', name: 'get_by_id_tarif_texture', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'TarifTexture retrieval',
        content: new Model(type: TarifTexture::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetByIdTarifTextureQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_by_id_tarif_texture',
            $response->toArray()
        );
    }
}

