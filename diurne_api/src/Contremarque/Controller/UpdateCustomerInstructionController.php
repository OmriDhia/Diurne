<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateCustomerInstruction\UpdateCustomerInstructionCommand;
use App\Contremarque\DTO\UpdateCustomerInstructionRequestDto;
use App\Contremarque\Entity\CustomerInstruction;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCustomerInstructionController extends CommandQueryController
{
    #[Route(
        '/api/update-customer-instruction/{customerInstructionId}',
        name: 'update_customer_instruction',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update Customer Instruction',
        content: new Model(type: CustomerInstruction::class)
    )]
    #[OA\RequestBody(
        description: 'Customer instruction data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'orderNumber', type: 'string'),
                new OA\Property(property: 'transmissionAdvice', type: 'string'),
                new OA\Property(property: 'customerComment', type: 'string'),
                new OA\Property(property: 'customerValidationDate', type: 'string', format: 'date'),
                new OA\Property(property: 'hasConstraints', type: 'boolean'),
                new OA\Property(property: 'hasValidateSample', type: 'boolean'),
                new OA\Property(property: 'hasFinitionInstruction', type: 'boolean'),
                new OA\Property(property: 'validatedSampleId', type: 'integer'),
                new OA\Property(property: 'finitionInstructionId', type: 'integer'),
                new OA\Property(property: 'constraintInstructionId', type: 'integer'),
                new OA\Property(property: 'objectId', type: 'integer'),
                new OA\Property(property: 'objectType', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $customerInstructionId,
        #[MapRequestPayload] UpdateCustomerInstructionRequestDto $requestDTO
    ): JsonResponse
    {
        try {
            $updateCustomerInstructionCommand = new UpdateCustomerInstructionCommand(
                $requestDTO->objectId,
                $requestDTO->objectType,
                (int)$customerInstructionId,
                $requestDTO->orderNumber,
                $requestDTO->transmissionAdvice,
                $requestDTO->customerComment,
                $requestDTO->customerValidationDate,
                $requestDTO->hasConstraints,
                $requestDTO->hasValidateSample,
                $requestDTO->hasFinitionInstruction,
                $requestDTO->validatedSampleId,
                $requestDTO->finitionInstructionId,
                $requestDTO->constraintInstructionId
            );

            $response = $this->handle($updateCustomerInstructionCommand);

            return SuccessResponse::create(
                'update_customer_instruction',
                $response->toArray(),
                'Customer instruction updated'
            );
        } catch (Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
