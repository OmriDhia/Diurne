<?php

namespace App\CheckingList\Controller\CheckingList;

use App\CheckingList\Bus\Command\DeleteCheckingList\DeleteCheckingListCommand;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\CheckingList\Entity\CheckingList;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteCheckingListController extends CommandQueryController
{
    #[Route('/api/checkingLists/{id}', name: 'checking_list_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Deleted', content: new Model(type: CheckingList::class))]
    #[OA\Parameter(name: 'id', description: 'Checking list id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new DeleteCheckingListCommand($id);
        $response = $this->handle($command);

        return SuccessResponse::create('checking_list_deleted', $response->toArray(), 'Checking list deleted', Response::HTTP_OK);
    }
}
