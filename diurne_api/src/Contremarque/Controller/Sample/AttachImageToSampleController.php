<?php

namespace App\Contremarque\Controller\Sample;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Sample\AttachImageToSampleCommand;
use App\Contremarque\Bus\Command\Sample\SampleResponse;
use App\Contremarque\DTO\AttachImageToSampleRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AttachImageToSampleController extends CommandQueryController
{
    #[Route(
        '/api/samples/{sampleId}/attach-image',
        name: 'associate_image_to_sample',
        methods: ['POST']
    )]
    #[OA\Response(
        response: 200,
        description: 'Associate Image to Sample',
        content: new Model(type: SampleResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageId', type: 'integer')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int                                                $sampleId,
        #[MapRequestPayload] AttachImageToSampleRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $associateImageCommand = new AttachImageToSampleCommand($sampleId, $requestDto->imageId);
        $result = $this->handle($associateImageCommand);

        if (!$result['success']) {
            return new JsonResponse([
                'code' => 400,
                'message' => 'Validation errors',
                'errors' => $result['errors']
            ], 400);
        }

        return SuccessResponse::create(
            'image_associated_to_sample',
            $result['data']->toArray(),
            'Image associated to sample successfully'
        );
    }
}