<?php

declare(strict_types=1);

namespace App\Event\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Event\Bus\Query\GetNomenclatures\GetNomenclaturesQuery;
use App\Event\Entity\EventNomenclature;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetNomenclaturesController extends CommandQueryController
{
    #[Route('/api/nomenclatures', name: 'get_nomenclatures', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get nomenclatures',
        content: new Model(type: EventNomenclature::class)
    )]
    #[OA\Tag(name: 'Event')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'event')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getNomenclatures = new GetNomenclaturesQuery();

        $response = $this->ask($getNomenclatures);

        return SuccessResponse::create(
            'get_nomenclatures',
            $response
        );
    }
}
