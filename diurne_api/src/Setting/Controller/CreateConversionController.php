<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Conversion\CreateConversionCommand;
use App\Setting\DTO\CreateConversionRequestDto;
use App\Setting\Entity\Conversion;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateConversionController extends CommandQueryController
{
    #[Route('/api/conversion', name: 'create_conversion', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Create Conversion',
        content: new Model(type: Conversion::class)
    )]
    #[OA\RequestBody(
        description: 'Conversion data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'currencyId', type: 'integer'),
                new OA\Property(property: 'conversionDate', type: 'string', format: 'date-time'),
                new OA\Property(property: 'coefficient', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateConversionRequestDto $requestDto
    ): JsonResponse {
        $conversion = $this->handle(new CreateConversionCommand($requestDto));

        return SuccessResponse::create(
            'create_conversion',
            $conversion->toArray()
        );
    }
}
