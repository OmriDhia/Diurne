<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Commercial\CommercialAttributionValidationCommand;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CommercialAttributionValidationController extends CommandQueryController
{
    #[Route('/api/CommercialAttributionValidation/{id}', name: 'commercial_attribution_validation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Commercial Attribution Validation',
        content: new Model(type: Customer::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $id
    ): JsonResponse {
        if (!$this->isGranted('validate', 'commercial')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $commercialAttributionValidationCommand = new CommercialAttributionValidationCommand(
            (int) $id
        );
        $commercialAttributionValidation = $this->handle($commercialAttributionValidationCommand);

        return SuccessResponse::create(
            'commercial_attribution_validation',
            $commercialAttributionValidation->toArray(),
            'Commercial Attribution Validation'

        );
    }
}
