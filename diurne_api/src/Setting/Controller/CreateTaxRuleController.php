<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TaxRule\CreateTaxRuleCommand;
use App\Setting\DTO\CreateTaxRuleRequestDto;
use App\Setting\Entity\TaxRule;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/createTaxRule', name: 'tax_rule_creation', methods: ['POST'])]
class CreateTaxRuleController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'TaxRule creation',
        content: new Model(type: TaxRule::class)
    )]
    #[OA\RequestBody(
        description: 'TaxRule data',
        content: new OA\JsonContent(
            required: ['taxRate', 'identifications'],
            properties: [
                new OA\Property(property: 'taxRate', type: 'string'),
                new OA\Property(
                    property: 'identifications',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'language_id', type: 'integer'),
                            new OA\Property(property: 'identification', type: 'string'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateTaxRuleRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createCommand = new CreateTaxRuleCommand(
            $requestDTO->taxRate,
            $requestDTO->identifications
        );

        $taxRuleResponse = $this->handle($createCommand);

        return SuccessResponse::create(
            $taxRuleResponse->toArray(),
            'tax_rule_creation'
        );
    }
}
