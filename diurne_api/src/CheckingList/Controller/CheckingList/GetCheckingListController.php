<?php

namespace App\CheckingList\Controller\CheckingList;

use App\CheckingList\Bus\Query\GetCheckingList\GetCheckingListQuery;
use App\CheckingList\DTO\CheckingList\GetCheckingListQueryDto;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use App\CheckingList\Entity\CheckingList;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class GetCheckingListController extends CommandQueryController
{
    #[Route('/api/checkingLists', name: 'checking_list_list', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: CheckingList::class)))]))]
    #[OA\Parameter(name: 'workshopOrderId', in: 'query', description: 'Filter by workshop order id', schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapQueryString] GetCheckingListQueryDto $query): JsonResponse
    {
        if (!$this->isGranted('read', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $checkingListQuery = new GetCheckingListQuery($query->workshopOrderId);
        $response = $this->ask($checkingListQuery);

        return SuccessResponse::create('checking_list_list', $response->toArray());
    }
}
