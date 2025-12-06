<?php

namespace App\Contremarque\Controller\ImageCommand;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateCustomerInstruction\CreateCustomerInstructionCommand;
use App\Contremarque\Bus\Command\CreateCustomerInstruction\CreateCustomerInstructionResponse;
use App\Contremarque\Bus\Command\ImageCommand\CreateImageCommandCommand;
use App\Contremarque\Bus\Command\ImageCommand\ImageCommandResponse;
use App\Contremarque\DTO\CreateCustomerInstructionRequestDto;
use App\Contremarque\DTO\CreateImageCommandRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Common\Bus\Command\CommandBus;
use App\Common\Bus\Query\QueryBus;
use App\Contremarque\Entity\CarpetDesignOrder;
use DateTimeImmutable;
use App\Contremarque\Entity\CustomerInstruction;

class CreateImageCommandController extends CommandQueryController
{
    public function __construct(
        private readonly QueryBus               $queryBus,
        private readonly CommandBus             $commandBus,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($queryBus, $commandBus);
    }

    #[Route(
        '/api/transmettre-object/to-adv',
        name: 'transmetre_object_adv',
        methods: ['POST']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully created  image command and customer instruction',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'Image command',
                    type: 'object',
                    ref: new Model(type: ImageCommandResponse::class)
                ),
                new OA\Property(
                    property: 'customer_instruction',
                    type: 'object',
                    ref: new Model(type: CreateCustomerInstructionResponse::class)
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'commandNumber', type: 'string'),
                new OA\Property(property: 'commercialComment', type: 'string'),
                new OA\Property(property: 'advComment', type: 'string'),
                new OA\Property(property: 'rn', type: 'string'),
                new OA\Property(property: 'studioComment', type: 'string'),
                new OA\Property(property: 'orderNumber', type: 'string'),
                new OA\Property(property: 'transmi_adv', type: 'string', format: 'date-time', nullable: true, description: 'Transmission to ADV timestamp - automatically set when the request is processed.'),
                new OA\Property(property: 'customerComment', type: 'string'),
                new OA\Property(property: 'objectId', type: 'integer'),
                new OA\Property(property: 'objectType', type: 'string'),
                new OA\Property(property: 'customerValidationDate', type: 'string', format: 'date'),
                new OA\Property(property: 'status_id', type: 'integer'),


            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateImageCommandRequestDto        $requestDTO,
        #[MapRequestPayload] CreateCustomerInstructionRequestDto $createCustomerInstructionRequestDto,
    ): JsonResponse
    {
        // Authorization check
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $this->entityManager->beginTransaction();

        try {
            $transmissionDate = new DateTimeImmutable();
            $responseData = [
                'image_command' => [],
                'customer_instruction' => [],
            ];

            // First operation - Image Command
            $createImageCommandCommand = new CreateImageCommandCommand(
                $requestDTO->objectId,
                $requestDTO->objectType,
                $requestDTO->commandNumber,
                $requestDTO->commercialComment,
                $requestDTO->advComment,
                $requestDTO->rn,
                $requestDTO->studioComment,
                (int)$requestDTO->status_id,
            );
            $imageResponse = $this->handle($createImageCommandCommand);
            $responseData['image_command'] = $imageResponse->toArray();

            // Conditional second operation - Customer Instruction
            if ($requestDTO->objectType === 'CarpetDesignOrder') {
                $carpetDesignOrder = $this->entityManager->getRepository(CarpetDesignOrder::class)
                    ->find((int)$requestDTO->objectId);
                if (!$carpetDesignOrder) {
                    throw new \Exception('CarpetDesignOrder not found');
                }

                $existingInstruction = $this->entityManager->getRepository(CustomerInstruction::class)
                    ->findOneBy([
                        'carpetDesignOrder' => $carpetDesignOrder,

                    ]);
                if ($existingInstruction) {
                    $existingInstruction->setTransmiAdv($transmissionDate);
                    $this->entityManager->flush();
                    $responseData['customer_instruction'] = $existingInstruction->toArray();
                } else {
                    // Create new instruction if doesn't exist
                    $createCustomerInstructionCommand = new CreateCustomerInstructionCommand(
                        $requestDTO->objectId,
                        $requestDTO->objectType,
                        $createCustomerInstructionRequestDto->orderNumber,
                        $transmissionDate,
                        $createCustomerInstructionRequestDto->customerValidationDate,
                        $createCustomerInstructionRequestDto->customerComment,
                    );
                    $customerInstructionResponse = $this->handle($createCustomerInstructionCommand);
                    $responseData['customer_instruction'] = $customerInstructionResponse->toArray();
                }
            }

            $this->entityManager->commit();
            return SuccessResponse::create('transmetre_object_adv', $responseData);
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            // Special handling for duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return new JsonResponse([
                    'code' => 409,
                    'message' => 'Resource already exists',
                    'details' => 'A customer instruction for this object already exists'
                ], 409);
            }

            return new JsonResponse([
                'code' => 500,
                'message' => 'Operation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
