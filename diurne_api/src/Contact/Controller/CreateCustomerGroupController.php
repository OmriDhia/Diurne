<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\CustomerGroup\CreateCustomerGroupCommand;
use App\Contact\DTO\CreateCustomerGroupRequestDto;
use App\Contact\Entity\CustomerGroup;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateCustomerGroupController extends CommandQueryController
{
    #[Route('/api/createCustomerGroup', name: 'customerGroup_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'CustomerGroup creation',
        content: new Model(type: CustomerGroup::class)
    )]
    #[OA\RequestBody(
        description: 'CustomerGroup data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] CreateCustomerGroupRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $createCustomerGroupCommand = new CreateCustomerGroupCommand($requestDTO->name);
        $customerGroupResponse = $this->handle($createCustomerGroupCommand);

        return SuccessResponse::create(
            'customerGroup_creation',
            $customerGroupResponse->toArray(),
            'CustomerGroup created successfully'

        );
    }
}
