<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ConvertAndCalculate\ConvertAndCalculateCommand;
use App\Contremarque\DTO\ConvertAndCalculateRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ConvertAndCalculateController extends CommandQueryController
{
    #[Route('/api/convert-and-calculate', name: 'convert_and_calculate', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Conversion of and calculate dimensions',
        content: new Model(type: ConvertAndCalculateRequestDto::class)
    )]
    #[OA\RequestBody(
        description: 'Dimensions data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'largCm', type: 'float'),
                new OA\Property(property: 'lngCm', type: 'float'),
                new OA\Property(property: 'largFeet', type: 'float'),
                new OA\Property(property: 'lngFeet', type: 'float'),
                new OA\Property(property: 'largInches', type: 'float'),
                new OA\Property(property: 'lngInches', type: 'float'),
                new OA\Property(property: 'InputUnit', type: 'string'),
                new OA\Property(property: 'quoteDetailId', type: 'int'),
                new OA\Property(property: 'currencyId', type: 'int'),
                new OA\Property(property: 'totalPriceHt', type: 'float'),
            ]
        )
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        #[MapRequestPayload] ConvertAndCalculateRequestDto $requestDTO
    ): JsonResponse {
        $convertCommand = new ConvertAndCalculateCommand(
            $requestDTO->largCm,
            $requestDTO->lngCm,
            $requestDTO->largFeet,
            $requestDTO->lngFeet,
            $requestDTO->largInches,
            $requestDTO->lngInches,
            $requestDTO->InputUnit,
            $requestDTO->quoteDetailId,
            $requestDTO->totalPriceHt ?? null,
            $requestDTO->currencyId ?? null,
        );
        $conversionResult = $this->handle($convertCommand);

        return SuccessResponse::create(
            'convert_and_calculate',
            $conversionResult->toArray()
        );
    }
}
