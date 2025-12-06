<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetAllTaxRule\GetAllTaxRulesQuery;
use App\Setting\Entity\TaxRule;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/taxRules', name: 'get_all_tax_rules', methods: ['GET'])]
class GetAllTaxRulesController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Get all tax rules',
        content: new Model(type: TaxRule::class)
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllTaxRulesQuery();
        $taxRules = $this->ask($query);

        return SuccessResponse::create(
            'get_all_tax_rules',
            $taxRules->toArray(),
            'Tax rules retrieved successfully'

        );
    }
}
