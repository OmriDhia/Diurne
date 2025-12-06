<?php
declare(strict_types=1);

namespace App\Workshop\Controller\WorkshopImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetWorkshopImageByWorkshopOrderId\GetWorkshopImagesByWorkshopOrderIdQuery;
use App\Workshop\Entity\WorkshopImage;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetWorkshopImagesByWorkshopOrderIdController extends CommandQueryController
{
    #[Route('/api/workshopImages/workshopOrder/{workshopOrderId}', name: 'workshop_image_get_by_workshop_order_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: WorkshopImage::class)))]))]
    #[OA\Parameter(name: 'workshopOrderId', in: 'path', description: 'Workshop order id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetWorkshopImagesByWorkshopOrderIdQuery($workshopOrderId));

        return SuccessResponse::create('workshop_image_list', $response->toArray());
    }
}
