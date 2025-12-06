<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Conversion\UpdateConversionCommand;
use App\Setting\DTO\UpdateConversionRequestDto;
use App\Setting\Entity\Conversion;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateConversionController extends CommandQueryController
{
    #[Route('/api/conversion/{conversionId}', name: 'update_conversion', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update Conversion',
        content: new Model(type: Conversion::class)
    )]
    #[OA\RequestBody(
        description: 'Updated conversion data',
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
        #[MapRequestPayload] UpdateConversionRequestDto $requestDto,
        int $conversionId
    ): JsonResponse {
        $response = $this->handle(new UpdateConversionCommand($conversionId, $requestDto));

        return SuccessResponse::create(
            'update_conversion',
            $response->toArray()
        );
    }
}
