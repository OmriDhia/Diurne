<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\Currency\CreateCurrencyCommand;
use App\Setting\DTO\CreateCurrencyRequestDto;
use App\Setting\Entity\Currency;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createCurrency', name: 'currency_creation', methods: ['POST'])]
class CreateCurrencyController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Currency creation',
        content: new Model(type: Currency::class)
    )]
    #[OA\RequestBody(
        description: 'Currency data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateCurrencyRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateCurrencyCommand(
            $requestDTO->name,
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'currency_creation',
            $response->toArray()
        );
    }
}
