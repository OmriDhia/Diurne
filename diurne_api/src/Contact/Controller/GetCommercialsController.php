<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetCommercials\GetCommercialsQuery;
use App\Contact\DTO\GetCommercialsQueryDto;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetCommercialsController extends CommandQueryController
{
    #[Route('/api/commercials', name: 'get_commercials', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get commercials',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'Commercial data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemPerPage', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapQueryString] GetCommercialsQueryDto $query,
    ): JsonResponse {
        if (!$this->isGranted('read', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getCommercials = new GetCommercialsQuery(
            $query->page ?? null,
            $query->itemPerPage ?? null,
            $query->filter->firstname ?? null,
            $query->filter->lastname ?? null,
            $query->filter->email ?? null,
        );

        $response = $this->ask($getCommercials);

        return SuccessResponse::create(
            'get_commercials',
            $response
        );
    }
}
