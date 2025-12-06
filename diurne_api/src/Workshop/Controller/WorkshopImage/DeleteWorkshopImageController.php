<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteWorkshopImage\DeleteWorkshopImageCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteWorkshopImageController extends CommandQueryController
{
    #[Route('/api/workshopImages/{id}', name: 'workshop_image_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop image deleted',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'status', type: 'string'),
            new OA\Property(property: 'message', type: 'string')
        ])
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop Image ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to delete workshop image'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            $response = $this->handle(new DeleteWorkshopImageCommand($id));

            return SuccessResponse::create(
                'workshop_image_deletion',
                $response->toArray(),
                'Workshop image deleted successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
    }
}