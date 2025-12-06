<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetAddressTypes\GetAddressTypesQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetAddressTypesController extends CommandQueryController
{
    #[Route('/api/addressTypes', name: 'get_addressTypes', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get addressTypes',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'AddressType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getAddressTypes = new GetAddressTypesQuery();
        $response = $this->ask($getAddressTypes);

        return SuccessResponse::create(
            'get_addressTypes',
            $response
        );
    }
}
