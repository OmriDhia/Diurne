<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Query\User\GetUsersMobileApp\GetUsersMobileAppQuery;
use App\MobileAppApi\Entity\UserMobileApp;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetUsersMobileAppListController extends CommandQueryController
{
    #[Route('/api/mobile/users', name: 'get_mobile_users', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserMobileApp::class))
        )
    )]
    public function __invoke(): JsonResponse
    {
        $query = new GetUsersMobileAppQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_mobile_users',
            $response->toArray(),
            'Users retrieved successfully'
        );
    }
}
