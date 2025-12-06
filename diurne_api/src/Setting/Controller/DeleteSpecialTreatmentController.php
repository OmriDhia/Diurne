<?php

namespace App\Setting\Controller;

use App\Setting\Entity\SpecialTreatment;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\SpecialTreatment\DeleteSpecialTreatmentCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/specialTreatment/{id}', name: 'delete_specialtreatment', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'SpecialTreatment deleted',
    content: new Model(type: SpecialTreatment::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteSpecialTreatmentController  extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteSpecialTreatmentCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'specialtreatment_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
