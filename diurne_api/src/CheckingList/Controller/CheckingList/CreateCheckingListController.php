<?php

namespace App\CheckingList\Controller\CheckingList;

use App\CheckingList\Bus\Command\CreateCheckingList\CreateCheckingListCommand;
use App\CheckingList\DTO\CheckingList\CreateCheckingListRequestDto;
use App\CheckingList\Entity\CheckingList;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCheckingListController extends CommandQueryController
{
    #[Route('/api/checkingLists', name: 'checking_list_create', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Created', content: new Model(type: CheckingList::class))]
    #[OA\RequestBody(
        description: 'Checking list data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'workshopOrderId', type: 'integer', example: 1),
                new OA\Property(property: 'author_id', type: 'integer', example: 1),
                new OA\Property(property: 'date', type: 'string', example: '2024-01-01'),
                new OA\Property(property: 'dateEndProd', type: 'string', example: '2024-01-02'),
                new OA\Property(property: 'comment', type: 'string', example: 'some comment')
            ]
        )
    )]
    #[OA\Tag(name: 'CheckingList')]
    public function __invoke(#[MapRequestPayload] CreateCheckingListRequestDto $dto): JsonResponse
    {
        if (!$this->isGranted('create', 'checkingList')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);

        }

        $command = new CreateCheckingListCommand(

            workshopOrderId: $dto->workshopOrderId,
            authorId: $dto->authorId,
            date: new \DateTime($dto->date),
            dateEndProd: new \DateTime($dto->dateEndProd),
            comment: $dto->comment
        );

        $response = $this->handle($command);

        return SuccessResponse::create('checking_list_created', $response->toArray(), 'Checking list created', Response::HTTP_CREATED);
    }
}
