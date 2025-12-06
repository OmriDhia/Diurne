<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\GetDiscountRules\GetDiscountRulesQuery;
use App\Setting\Entity\DiscountRule;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetDiscountRulesController extends CommandQueryController
{
    #[Route('/api/discountRules', name: 'get_discountRules', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get discountRules',
        content: new Model(type: DiscountRule::class)
    )]
    #[OA\RequestBody(
        description: 'DiscountRule data', )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getDiscountRules = new GetDiscountRulesQuery();
        $response = $this->ask($getDiscountRules);

        return SuccessResponse::create(
            'get_discountRules',
            $response
        );
    }
}
