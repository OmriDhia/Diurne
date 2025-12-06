<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Contremarque\CreateContremarqueCommand;
use App\Contremarque\DTO\CreateContremarqueRequestDto;
use App\Contremarque\Entity\Contremarque;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateContremarqueController extends CommandQueryController
{
    #[Route('/api/createContremarque', name: 'contremarque_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Contremarque creation',
        content: new Model(type: Contremarque::class)
    )]
    #[OA\RequestBody(
        description: 'Contremarque data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'designation', type: 'string'),
                new OA\Property(property: 'destination_location', type: 'string'),
                new OA\Property(property: 'target_date', type: 'datetime'),
                new OA\Property(property: 'customer_id', type: 'integer'),
                new OA\Property(property: 'customerDiscount_id', type: 'integer'),
                new OA\Property(property: 'prescriber_id', type: 'integer'),
                new OA\Property(property: 'commission', type: 'float'),
                new OA\Property(property: 'commission_on_deposit', type: 'boolean'),
            ]
        ))]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateContremarqueRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createContremarqueCommand = new CreateContremarqueCommand(
            $requestDTO->designation,
            $requestDTO->destination_location,
            $requestDTO->target_date,
            $requestDTO->customer_id,
            $requestDTO->customerDiscount_id,
            $requestDTO->prescriber_id,
            $requestDTO->commission,
            $requestDTO->commission_on_deposit,
        );
        $contremarqueResponse = $this->handle($createContremarqueCommand);

        return SuccessResponse::create(
            'contremarque_creation',
            $contremarqueResponse->toArray()
        );
    }
}
