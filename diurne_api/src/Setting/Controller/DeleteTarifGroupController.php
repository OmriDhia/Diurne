<?php

namespace App\Setting\Controller;

use App\Setting\Entity\TarifGroup;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TarifGroup\DeleteTarifGroupCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tarifGroup/{id}', name: 'delete_tarifgroup', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'TarifGroup deleted',
    content: new Model(type: TarifGroup::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteTarifGroupController  extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteTarifGroupCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'tarifgroup_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
