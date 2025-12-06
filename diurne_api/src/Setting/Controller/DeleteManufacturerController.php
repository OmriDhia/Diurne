<?php

namespace App\Setting\Controller;

use App\Setting\Entity\Manufacturer;
use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Manufacturer\DeleteManufacturerCommand;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer/{id}', name: 'delete_manufacturer', methods: ['DELETE'])]
#[OA\Response(
    response: 200,
    description: 'Manufacturer deleted',
    content: new Model(type: Manufacturer::class)
)]
#[OA\Tag(name: 'Setting')]
class DeleteManufacturerController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteManufacturerCommand($id);

        try {
            $response = $this->handle($deleteCommand);
            return SuccessResponse::create(
                'manufacturer_delete',
                ['status' => 'success', 'data' => $response->toArray()]
            );
        } catch (Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
