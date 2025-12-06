<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Conversion;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Conversion\DeleteConversionCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/conversion/{id}', name: 'delete_conversion', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Conversion deleted',
    content: new Model(type: Conversion::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteConversionController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteConversionCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'conversion_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
