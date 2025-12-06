<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use DateTimeImmutable;
use InvalidArgumentException;
use DateTimeException;
use App\Common\Bus\Command\Command;
use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\Query;
use App\Common\Bus\Query\QueryBus;
use App\Common\Bus\Query\QueryResponse;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Contact\AssignIntermediaryToCustomerCommand;
use App\Contact\DTO\AssignIntermediaryToCustomerRequestDto;
use App\Contact\Entity\Customer;
use App\Contact\Entity\IntermediaryType;
use App\Contact\Repository\IntermediaryTypeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/AssignIntermediaryToCustomer', name: 'assign_intermediary_to_customer', methods: ['POST'])]
#[OA\Tag(name: 'Contact')]
#[OA\Response(
    response: 200,
    description: 'Intermediary assigned to customer successfully',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'status', type: 'string', example: 'success'),
            new OA\Property(property: 'message', type: 'string', example: 'assign_intermediary_to_customer'),
            new OA\Property(property: 'data', ref: new Model(type: Customer::class)),
        ]
    )
)]
#[OA\Response(
    response: 400,
    description: 'Invalid request data',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'status', type: 'string', example: 'error'),
            new OA\Property(property: 'message', type: 'string', example: 'Invalid request data'),
            new OA\Property(property: 'details', type: 'object', example: ['field' => 'Error message']),
        ]
    )
)]
#[OA\Response(
    response: 401,
    description: 'Unauthorized',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'status', type: 'string', example: 'error'),
            new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to assign intermediaries.'),
        ]
    )
)]
#[OA\Response(
    response: 404,
    description: 'Resource not found',
    content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'status', type: 'string', example: 'error'),
            new OA\Property(property: 'message', type: 'string', example: 'Intermediary type not found.'),
        ]
    )
)]
#[OA\RequestBody(
    description: 'Data to assign an intermediary to a customer',
    required: true,
    content: new OA\JsonContent(
        ref: new Model(type: AssignIntermediaryToCustomerRequestDto::class)
    )
)]
final class AssignIntermediaryToCustomerController extends CommandQueryController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly IntermediaryTypeRepository $intermediaryTypeRepository
    ) {
        parent::__construct($queryBus, $commandBus);
    }

    public function __invoke(
        #[MapRequestPayload] AssignIntermediaryToCustomerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(["code" => 401, "message" => 'Unauthorized to access this content'], 401);
        }

        try {
            // Handle fromDate and toDate
            $fromDate = ($requestDTO->fromDate && !empty($requestDTO->fromDate) && $requestDTO->fromDate !== 'Invalid date')
                ? new DateTimeImmutable(str_replace('/', '-', $requestDTO->fromDate))
                : new DateTimeImmutable();
            $toDate = $requestDTO->toDate
                ? new DateTimeImmutable(str_replace('/', '-', $requestDTO->toDate))
                : null;

            // Handle intermediaryTypeId (default to "agent" if not provided)
            $intermediaryTypeId = $requestDTO->intermediaryTypeId;
            if (empty($intermediaryTypeId)) {
                $agentType = $this->intermediaryTypeRepository->findOneBy(['name' => 'agent']);
                if (!$agentType instanceof IntermediaryType) {
                    throw new BadRequestHttpException('Default intermediary type "agent" not found.');
                }
                $intermediaryTypeId = $agentType->getId();
            }

            // Create and handle the command
            $assignIntermediaryToCustomerCommand = new AssignIntermediaryToCustomerCommand(
                intermediaryId: $requestDTO->intermediaryId,
                customerId: $requestDTO->customerId,
                intermediaryTypeId: $intermediaryTypeId,
                fromDate: $fromDate,
                toDate: $toDate
            );

            $assignIntermediaryToCustomer = $this->handle($assignIntermediaryToCustomerCommand);

            return SuccessResponse::create(
                'assign_Intermediary_to_customer',
                $assignIntermediaryToCustomer->toArray(),

            );
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        } catch (DateTimeException $e) {
            throw new BadRequestHttpException('Invalid date format: ' . $e->getMessage(), $e);
        }
    }
}
