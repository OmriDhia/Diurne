<?php

declare(strict_types=1);

namespace App\Setting\Controller\TarifTexture;

use RuntimeException;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TarifTexture\DeleteTarifTextureCommand;
use App\Setting\Entity\TarifTexture;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tarifTexture/{id}', name: 'tariftexture_delete', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'TarifTexture deleted',
    content: new Model(type: TarifTexture::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteTarifTextureController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteTarifTextureCommand($id);

        /** @var \App\Common\Bus\Command\Command $cmd */
        $cmd = $deleteCommand;

        try {
            $response = $this->handle($cmd);

            return SuccessResponse::create(
                'tariftexture_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (RuntimeException $e) {
            $code = $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 500;
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], $code);
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

