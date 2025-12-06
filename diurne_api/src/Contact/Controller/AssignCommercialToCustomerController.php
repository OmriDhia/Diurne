<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Commercial\AssignCommercialToCustomerCommand;
use App\Contact\DTO\AssignCommercialToCustomerRequestDto;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AssignCommercialToCustomerController extends CommandQueryController
{
    #[Route('/api/AssignCommercialToCustomer', name: 'assign_commercial-to-customer', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Assign Commercial to customer',
        content: new Model(type: Customer::class)
    )]
    #[OA\RequestBody(
        description: 'Commercial to customer data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'commercialId', type: 'int'),
                new OA\Property(property: 'customerId', type: 'int'),
                new OA\Property(property: 'isValidated', type: 'boolean'),
                new OA\Property(property: 'fromDate', type: 'datetime'),
                new OA\Property(property: 'toDate', type: 'datetime'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] AssignCommercialToCustomerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $assignCommercialToCustomerCommand = new AssignCommercialToCustomerCommand(
            (int) $requestDTO->commercialId,
            (int) $requestDTO->customerId
        );
        $assignCommercialToCustomerCommand->setStatus($requestDTO->status);
        $assignCommercialToCustomerCommand->setFromDate($requestDTO->fromDate);
        $assignCommercialToCustomerCommand->setToDate($requestDTO->toDate);
        $assignCommercialToCustomer = $this->handle($assignCommercialToCustomerCommand);

        return SuccessResponse::create(
            'assign_commercial-to-customer',
            $assignCommercialToCustomer->toArray(),
            'Commercial assigned to customer successfully'
        );
    }
}
