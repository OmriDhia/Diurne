<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateCustomerInstruction\CreateCustomerInstructionCommand;
use App\Contremarque\DTO\CreateCustomerInstructionRequestDto;
use App\Contremarque\Entity\CustomerInstruction;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCustomerInstructionController extends CommandQueryController
{
    #[Route('/api/create-customer-instruction', name: 'create_customer_instruction', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Customer Instruction',
        content: new Model(type: CustomerInstruction::class)
    )]
    #[OA\RequestBody(
        description: 'Customer instruction data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'orderNumber', type: 'string'),
                new OA\Property(property: 'transmi_adv', type: 'string', format: 'date-time', nullable: true, description: 'Transmission to ADV timestamp - automatically set when available.'),
                new OA\Property(property: 'customerComment', type: 'string'),
                new OA\Property(property: 'objectId', type: 'integer'),
                new OA\Property(property: 'objectType', type: 'string'),
                new OA\Property(property: 'customerValidationDate', type: 'string', format: 'date'),

            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateCustomerInstructionRequestDto $requestDTO
    ): JsonResponse
    {
        $createCustomerInstructionCommand = new CreateCustomerInstructionCommand(
            $requestDTO->objectId,
            $requestDTO->objectType,
            $requestDTO->orderNumber,
            null,
            $requestDTO->customerValidationDate,
            $requestDTO->customerComment,
        );

        $response = $this->handle($createCustomerInstructionCommand);

        return SuccessResponse::create(
            'create_customer_instruction',
            $response->toArray()
        );
    }
}
