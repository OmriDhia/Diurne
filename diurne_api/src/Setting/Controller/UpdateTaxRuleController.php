<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\TaxRule\UpdateTaxRuleCommand;
use App\Setting\DTO\UpdateTaxRuleRequestDto;
use App\Setting\Entity\TaxRule;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/updateTaxRule/{id}', name: 'tax_rule_update', methods: ['PUT'])]
class UpdateTaxRuleController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'TaxRule update',
        content: new Model(type: TaxRule::class)
    )]
    #[OA\RequestBody(
        description: 'TaxRule update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'taxRate', type: 'string', nullable: true),
                new OA\Property(
                    property: 'identifications',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'language_id', type: 'integer'),
                            new OA\Property(property: 'identification', type: 'string'),
                        ]
                    ),
                    nullable: true
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateTaxRuleRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateCommand = new UpdateTaxRuleCommand(
            $id,
            $requestDTO->taxRate,
            $requestDTO->identifications
        );

        $taxRuleResponse = $this->handle($updateCommand);

        return SuccessResponse::create(
            $taxRuleResponse->toArray(),
            'tax_rule_update'
        );
    }
}
