<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Commercial\CommercialAttributionAnnulationCommand;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CommercialAttributionAnnulationController extends CommandQueryController
{
    #[Route('/api/CommercialAttributionAnnulation/{id}', name: 'commercial_attribution_annulation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Commercial Attribution Annulation',
        content: new Model(type: Customer::class)
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        $id
    ): JsonResponse {
        if (!$this->isGranted('validate', 'commercial')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $commercialAttributionAnnulationCommand = new CommercialAttributionAnnulationCommand(
            (int) $id
        );
        $commercialAttributionAnnulation = $this->handle($commercialAttributionAnnulationCommand);

        return SuccessResponse::create(
            'commercial_attribution_annulation',
            $commercialAttributionAnnulation->toArray(),
            'Commercial Attribution Annulation'

        );
    }
}
