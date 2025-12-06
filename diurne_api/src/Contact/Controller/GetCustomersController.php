<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetCustomers\GetCustomersQuery;
use App\Contact\DTO\GetCustomersQueryDto;
use App\Contact\Entity\Customer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class GetCustomersController extends CommandQueryController
{
    #[Route('/api/customers', name: 'get_customers', methods: ['GET'])]
    #[Route('/api/customers/all', name: 'get_all_customers', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get customers',
        content: new Model(type: Customer::class)
    )]
    #[OA\RequestBody(
        description: 'Customer data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemsPerPage', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        Request $request,
        #[MapQueryString] GetCustomersQueryDto $query,
    ): JsonResponse|StreamedResponse {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started
        $currentUser = $session->get('user');

        if ('get_all_customers' === $request->attributes->get('_route')) {
            $query->page = null;
            $query->itemsPerPage = null;
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
            $query->filter->city ?? null,
            $query->filter->zip_code ?? null,
            $query->filter->countryId ?? null,
            $query->filter->hasWrongAddress ?? null,
            $query->filter->hasValidAddress ?? null,
            $query->filter->mailingLanguageId ?? null,
            $query->filter->contactMailing ?? null,
            $query->orderBy ?? null,
            $query->orderWay ?? null,
            $query->filter->is_intermediary ?? null,
            $query->filter->customerName ?? null,
            $query->exportFormat ?? null,
            $query->filter->customerGroupId ?? null,
            $currentUser->getId(),
            (int) (!empty($query->filter) ? ($query->filter->commercialId ?? null) : null),
        );

        $response = $this->ask($getCustomers);

        if ($query->exportFormat) {
            return $response->export();
        }

        return SuccessResponse::create(
            'get_customers',
            $response
        );
    }
}
