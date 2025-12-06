<?php

namespace App\CheckingList\Controller\CheckingList;

use App\CheckingList\Bus\Command\UpdateCheckingList\UpdateCheckingListCommand;
use App\CheckingList\DTO\CheckingList\UpdateCheckingListRequestDto;
use App\CheckingList\Entity\CheckingList;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCheckingListController extends CommandQueryController
{
    #[Route('/api/checkingLists/{id}', name: 'checking_list_update', methods: ['PUT'])]
    #[OA\Response(response: 200, description: 'Updated', content: new Model(type: CheckingList::class))]
    #[OA\RequestBody(
        description: 'Update checking list data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'authorId', type: 'integer', example: 1),
                new OA\Property(property: 'date', type: 'string', example: '2024-01-01'),
                new OA\Property(property: 'dateEndProd', type: 'string', example: '2024-01-02'),
                new OA\Property(property: 'comment', type: 'string', example: 'comment update')
            ]
        )
    )]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(int $id, #[MapRequestPayload] UpdateCheckingListRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('update', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $command = new UpdateCheckingListCommand(
            id: $id,
            authorId: $dto->authorId,
            date: $dto->date ? new \DateTime($dto->date) : null,
            dateEndProd: $dto->dateEndProd ? new \DateTime($dto->dateEndProd) : null,
            comment: $dto->comment
        );

        $response = $this->handle($command);

        return SuccessResponse::create('checking_list_updated', $response->toArray(), 'Checking list updated');
    }
}
