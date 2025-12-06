<?php

namespace App\CheckingList\Controller\CheckingList;

use App\CheckingList\Bus\Query\GetCheckingListById\GetCheckingListByIdQuery;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use App\CheckingList\Entity\CheckingList;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetCheckingListByIdController extends CommandQueryController
{
    #[Route('/api/checkingLists/{id}', name: 'checking_list_get_by_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Single', content: new Model(type: CheckingList::class))]
    #[OA\Parameter(name: 'id', description: 'Checking list id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $query = new GetCheckingListByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create('checking_list_item', $response->toArray());
    }
}
