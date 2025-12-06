<?php

namespace App\Contremarque\Controller\Sample;

use Exception;
use App\Contremarque\Bus\Command\Sample\CreateSampleCommand;
use App\Contremarque\Bus\Command\Sample\SampleResponse;
use App\Contremarque\DTO\CreateSampleRequestDto;
use App\Contremarque\ValueObject\Dimension;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CreateSampleController extends CommandQueryController
{
    #[Route(
        path: '/api/samples',
        name: 'create_sample',
        methods: ['POST']
    )]
    #[OA\Response(
        response: 201,
        description: 'Sample created successfully',
        content: new Model(type: SampleResponse::class)
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation errors',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 400),
                new OA\Property(property: 'message', type: 'string', example: 'Validation errors'),
                new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'))
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Authentication required',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 401),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to access this content')
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden - Insufficient permissions',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 403),
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to create a sample')
            ]
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer', nullable: true, example: 1),
                new OA\Property(property: 'locationId', type: 'integer', example: 1),
                new OA\Property(property: 'collectionId', type: 'integer', nullable: true, example: 1),
                new OA\Property(property: 'modelId', type: 'integer', nullable: true, example: 1),
                new OA\Property(property: 'qualityId', type: 'integer', nullable: true, example: 1),
                new OA\Property(property: 'diCommandNumber', type: 'string', maxLength: 50, example: 'SAMPLE001'),
                new OA\Property(
                    property: 'dimension',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'width', type: 'string', example: '100.5'),
                        new OA\Property(property: 'height', type: 'string', example: '200.75'),
                    ]
                ),
                new OA\Property(property: 'rn', type: 'string', maxLength: 50, nullable: true, example: 'RN123'),
                new OA\Property(property: 'transmissionDate', type: 'string', format: 'date-time', nullable: true, example: '2025-03-17T12:00:00Z'),
                new OA\Property(property: 'customerComment', type: 'string', nullable: true, example: 'Customer comment'),
                new OA\Property(property: 'imageIds', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3]),
                new OA\Property(property: 'attachmentIds', type: 'array', items: new OA\Items(type: 'integer'), example: [4, 5]),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateSampleRequestDto $requestDto
    ): JsonResponse {
        // Check authorization
        if (!$this->isGranted('create', 'contremarque')) {
            throw new AccessDeniedHttpException('Insufficient permissions to create a sample');
        }

        try {
            // Map DTO to Command
            $command = new CreateSampleCommand(
                carpetDesignOrderId: $requestDto->carpetDesignOrderId,
                locationId: $requestDto->locationId,
                collectionId: $requestDto->collectionId,
                modelId: $requestDto->modelId,
                qualityId: $requestDto->qualityId,
                diCommandNumber: $requestDto->diCommandNumber,
                rn: $requestDto->rn,
                transmissionDate: $requestDto->transmissionDate,
                customerComment: $requestDto->customerComment,
                imageIds: $requestDto->imageIds,
                attachmentIds: $requestDto->attachmentIds,
                dimension: $requestDto->dimension
            );

            // Handle the command using the parent controller's handle method
            /** @var SampleResponse $response */
            $response = $this->handle($command);

            // Return a 201 Created response using SuccessResponse
            return SuccessResponse::create(
                'sample_created',
                $response->toArray(),
                'Sample created successfully'
            );
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(
                data: [
                    'code' => 400,
                    'message' => 'Validation errors',
                    'errors' => explode(', ', $e->getMessage()),
                ],
                status: Response::HTTP_BAD_REQUEST
            );
        } catch (Exception $e) {
            return new JsonResponse(
                data: [
                    'code' => 500,
                    'message' => 'Internal server error',
                    'errors' => [$e->getMessage()],
                ],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
