<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetCustomers\GetCustomersQuery;
use App\Contact\DTO\GetCustomersFilterDto;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetIntermediariesController extends CommandQueryController
{
    #[Route('/api/intermediaries', name: 'get_intermediaries', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get intermediaries',
        content: new Model(type: Customer::class)
    )]
    #[OA\RequestBody(
        description: 'Intermediary data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemPerPage', type: 'int'),
                new OA\Property(property: 'orderBy', type: 'string'),
                new OA\Property(property: 'orderWay', type: 'string'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapQueryString] GetCustomersFilterDto $query,
    ): JsonResponse {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getCustomers = new GetCustomersQuery(
            $query->page ?? null,
            $query->itemsPerPage ?? null,
            $query->filter->firstname ?? null,
            $query->filter->lastname ?? null,
            $query->filter->commercial ?? null,
            $query->filter->is_agent ?? null,
            $query->filter->is_prescripteur ?? null,
            $query->filter->prescripteur ?? null,
            $query->filter->active ?? null,
            $query->filter->hasInvalidCommercial ?? null,
            $query->filter->hasOnlyOneContact ?? null,
            $query->filter->socialReason ?? null,
            $query->filter->tva_ce ?? null,
            $query->filter->website ?? null,
            $query->filter->customerGroupId ?? null,
            $query->filter->city ?? null,
            $query->filter->zip_code ?? null,
            $query->filter->countryId ?? null,
            $query->filter->hasWrongAddress ?? null,
            $query->filter->hasValidAddress ?? null,
            $query->filter->mailingLanguageId ?? null,
            $query->filter->contactMailing ?? null,
            $query->orderBy ?? null,
            $query->orderWay ?? null,
            true,
            $query->filter->customerName ?? null,
        );

        $response = $this->ask($getCustomers);

        return SuccessResponse::create(
            'get_intermediaries',
            $response
        );
    }
}
