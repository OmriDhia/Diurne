<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopOrder;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteWorkshopOrder\DeleteWorkshopOrderCommand;
use App\Workshop\Entity\WorkshopOrder;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteWorkshopOrderController extends CommandQueryController
{
    #[Route('/api/workshopOrders/{id}', name: 'workshop_order_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Workshop order deleted successfully',
        content: new Model(type: WorkshopOrder::class)
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop Order ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteWorkshopOrderCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create(
            'workshop_order_deleted',
            $response->toArray(),
            'Workshop order deleted successfully',
            Response::HTTP_OK
        );
    }
}